<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use App\Models\EventBudget;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventCommittee;
use App\Models\Certificate;
use App\Models\GeneratedDocument;
use App\Models\EventRundownItem;
use App\Models\Partner;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ExpenseReport;
use App\Models\ExpenseCategory;
use Illuminate\Validation\Rule;
use App\Exports\RundownExport;
use App\Exports\RundownTemplateExport;
use App\Exports\CertificateTemplateExport;
use App\Imports\RundownImport;
use App\Imports\CertificateImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use ZipArchive;

class EventController extends Controller
{
    // =========================
    // SHOW EVENT + KANBAN
    // =========================
    public function show($id)
    {
        $event = \App\Models\Event::visibleTo(auth()->user())->with([
            'committees.user',
            'organization.members',
            'budgets.category',
            'budgets.user',
            'category',
            'rundownItems.assignedCommittee.user',
            'rundownItems.assignedCommittee.division',
            'partners.pic',
            'certificates',
            'documents.creator',
        ])->findOrFail($id);


        $divisions = \App\Models\Division::all();
        $budgetCategories = BudgetCategory::orderBy('nama_kategori')->get();
        $members = $event->organization->members;
        $availableMembers = $members->reject(function ($member) use ($event) {
            return $event->committees->contains('id_user', $member->id_user);
        })->values();

        // Aturan Hak Akses Pengelolaan Modul
        $canManageRundown = $event->canManageRundownBy(auth()->user());
        $canManagePartner = $event->canManagePartnerBy(auth()->user());
        $canManageCertificate = $event->canManageCertificateBy(auth()->user());
        $canManageDocument = $event->canManageDocumentBy(auth()->user());
        $canManageTasks = $event->canManageBy(auth()->user()); // 🔥 Ditambahkan agar tidak undefined

        // Ambil data Tugas (Tasks) & Keuangan
        $tasks = Task::with('assignee')->where('id_event', $id)->get();
        $expenses = ExpenseReport::with(['category', 'user'])->where('id_event', $id)->latest('id_expense')->get();
        $expenseCategories = ExpenseCategory::all();

        // Hitung Progress Pengerjaan
        $total = $tasks->count();
        $done = $tasks->where('status', 'done')->count();
        $progress = $total > 0 ? round(($done / $total) * 100) : 0;

        return view('events.show', compact(
            'event',
            'tasks',
            'divisions',
            'budgetCategories',
            'members',
            'availableMembers',
            'canManageRundown',
            'canManagePartner',
            'canManageCertificate',
            'canManageDocument',
            'canManageTasks', // 🔥 Dimasukkan ke dalam compact
            'progress',
            'expenses',
            'expenseCategories'
        ));
    }

    public function rundownList()
    {
        $events = Event::visibleTo(auth()->user())
            ->latest()
            ->get();

        return view(
            'events.rundown-list',
            compact('events')
        );
    }

    public function rundownPage($id)
    {
        $event = Event::visibleTo(auth()->user())
            ->with('rundowns')
            ->findOrFail($id);

        $canManageRundown =
            $event->canManageRundownBy(auth()->user());

        return view(
            'events.rundown',
            compact(
                'event',
                'canManageRundown'
            )
        );
    }

    // =========================
    // API PROGRESS (AJAX)
    // =========================
    public function progress($id)
    {
        $tasks = \App\Models\Task::where('id_event', $id)->get();

        $total = $tasks->count();
        $done = $tasks->where('status', 'done')->count();

        $progress = $total > 0 ? round(($done / $total) * 100) : 0;

        return response()->json([
            'progress' => $progress
        ]);
    }

    public function getTasks($id)
    {
        $tasks = \App\Models\Task::with('assignee')
            ->where('id_event', $id)
            ->get();

        return response()->json($tasks);
    }

    public function create()
    {
        $organizations = auth()->user()->organizations;
        $categories = EventCategory::orderBy('nama_kategori')->get();

        return view('events.create', compact('organizations', 'categories'));
    }

    public function index(Request $request)
{
    $statusFilter = $request->query('status', 'all');
    $now = Carbon::now();

    // 1. Ambil query awal yang sudah terkunci dengan scope hak akses data
    $query = Event::visibleTo(auth()->user())->with('committees');

    // 2. Tambahkan logika filter dinamis berdasarkan waktu saat ini
    if ($statusFilter && $statusFilter !== 'all') {
    $query->where(function ($q) use ($statusFilter, $now) {
        if ($statusFilter === 'Planning') {
            // Waktu sekarang belum mencapai tanggal mulai
            $q->where('tgl_mulai', '>', $now);
        } elseif ($statusFilter === 'Ongoing') {
            // Ada tgl_selesai dan sekarang berada di antaranya OR tgl_selesai kosong tapi mulai hari ini
            $q->where(function ($sub) use ($now) {
                $sub->where('tgl_mulai', '<=', $now)
                    ->where('tgl_selesai', '>=', $now);
            })->orWhere(function ($sub) use ($now) {
                $sub->whereNull('tgl_selesai')
                    ->whereDate('tgl_mulai', '=', $now->toDateString());
            });
        } elseif ($statusFilter === 'Done') {
            // Sudah melewati tgl_selesai OR tgl_selesai kosong tapi sudah lewat hari dari tgl_mulai
            $q->where(function ($sub) use ($now) {
                $sub->whereNotNull('tgl_selesai')
                    ->where('tgl_selesai', '<', $now);
            })->orWhere(function ($sub) use ($now) {
                $sub->whereNull('tgl_selesai')
                    ->where('tgl_mulai', '<', $now->startOfDay());
            });
        }
    });
}

    // 3. Ambil data dengan urutan terbaru
    $events = $query->latest()->get();

    return view('events.index', compact('events'));
}

    public function edit($id)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($id);
        abort_unless($event->canManageBy(auth()->user()), 403, 'Tidak punya akses');
        $organizations = auth()->user()->organizations;
        $categories = EventCategory::orderBy('nama_kategori')->get();

        return view('events.edit', compact('event', 'organizations', 'categories'));
    }

    public function update(Request $request, $id)
{
    // 1. VALIDASI DATA (Wajib daftarkan field baru agar tidak lolos/diabaikan)
    $validatedData = $request->validate([
        'nama_event'         => 'required|string|max:255',
        'id_org'             => 'required',
        'id_event_category'  => 'required',
        'tgl_mulai'          => 'required|date',
        'tgl_selesai'        => 'nullable|date|after_or_equal:tgl_mulai',
        
        // Tambahkan ini (nullable artinya boleh kosong/tidak wajib diisi saat edit awal)
        'latar_belakang'     => 'nullable|string',
        'tujuan'             => 'nullable|string',
    ]);

    // 2. AMBIL DATA EVENT BERDASARKAN ID
    $event = Event::findOrFail($id);

    // 3. UPDATE DATA KE DATABASE
    $event->update([
        'nama_event'        => $request->nama_event,
        'id_org'            => $request->id_org,
        'id_event_category' => $request->id_event_category,
        'tgl_mulai'         => $request->tgl_mulai,
        'tgl_selesai'       => $request->tgl_selesai,
        
        // Simpan field baru ke tabel events
        'latar_belakang'    => $request->latar_belakang,
        'tujuan'            => $request->tujuan,
    ]);

    // 4. REDIRECT KEMBALI DENGAN SESSION SUCCESS
    return redirect()->back()->with('success', 'Detail informasi master event berhasil diperbarui!');
}

    public function storeBudget(Request $request, $id)
    {
        $request->validate([
            'id_category' => 'required|exists:budget_categories,id_category',
            'keterangan' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'nominal_rencana' => 'required|numeric|min:0',
        ]);

        $event = Event::visibleTo(auth()->user())->findOrFail($id);
        abort_unless($event->canManageBy(auth()->user()), 403, 'Tidak punya akses');

        EventBudget::create([
            'id_event' => $event->id_event,
            'id_user' => auth()->user()->id_user,
            'id_category' => $request->id_category,
            'keterangan' => $request->keterangan,
            'qty' => $request->qty,
            'nominal_rencana' => $request->nominal_rencana,
        ]);

        return back()->with('success', 'Budget berhasil ditambahkan');
    }

    public function updateBudget(Request $request, $eventId, $budgetId)
    {
        $request->validate([
            'id_category' => 'required|exists:budget_categories,id_category',
            'keterangan' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'nominal_rencana' => 'required|numeric|min:0',
        ]);

        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageBy(auth()->user()), 403, 'Tidak punya akses');

        $budget = EventBudget::where('id_budget', $budgetId)
            ->where('id_event', $eventId)
            ->firstOrFail();

        $budget->update([
            'id_category' => $request->id_category,
            'keterangan' => $request->keterangan,
            'qty' => $request->qty,
            'nominal_rencana' => $request->nominal_rencana,
        ]);

        return back()->with('success', 'Budget berhasil diupdate');
    }

    public function destroyBudget($eventId, $budgetId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageBy(auth()->user()), 403, 'Tidak punya akses');

        $budget = EventBudget::where('id_budget', $budgetId)
            ->where('id_event', $eventId)
            ->firstOrFail();

        $budget->delete();

        return back()->with('success', 'Budget berhasil dihapus');
    }

    public function budgetList()
    {
        $events = Event::visibleTo(auth()->user())
            ->latest()
            ->get();

        return view(
            'events.budget-list',
            compact('events')
        );
    }

    public function budgetPage($eventId)
    {
        $event = Event::visibleTo(auth()->user())
            ->with([
                'budgets.category',
                'budgets.user'
            ])
            ->findOrFail($eventId);

        $budgetCategories =
            BudgetCategory::orderBy(
                'nama_kategori'
            )->get();

        return view(
            'events.budget',
            compact(
                'event',
                'budgetCategories'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_event' => 'required|unique:events,nama_event',
            'id_org' => 'required|exists:organizations,id_org',
            'id_event_category' => 'required|exists:event_categories,id_event_category',
            'tgl_mulai' => 'required|date',
        ]);

        $isMember = auth()->user()->organizations
            ->contains('id_org', $request->id_org);

        if (!$isMember) {
            abort(403, 'Bukan anggota organization ini');
        }

        $event = \App\Models\Event::create([
            'nama_event' => $request->nama_event,
            'id_org' => $request->id_org,
            'id_creator' => auth()->user()->id_user,
            'id_event_category' => $request->id_event_category,
            'tgl_mulai' => $request->tgl_mulai,
        ]);

    $defaultDivision = \App\Models\Division::first();
    
    // Pastikan ada divisi di database agar tidak error null
    $idDivisi = $defaultDivision ? $defaultDivision->id_divisi : null;

        \App\Models\EventCommittee::create([
            'id_event' => $event->id_event,
            'id_user' => auth()->user()->id_user,
            'id_divisi' => $idDivisi,
            'jabatan' => 'Ketua Acara',
        ]);

        return redirect('/events/' . $event->id_event);
    }

    public function assignMember(Request $request, $id)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'id_user' => 'required|exists:users,id_user',
            'id_divisi' => 'required|exists:divisions,id_divisi',
            'jabatan' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('events.show', $id)
                ->withErrors($validator)
                ->withInput()
                ->with('open_tab', 'committee');
        }

        $event = Event::visibleTo(auth()->user())->findOrFail($id);
        abort_unless($event->canManageBy(auth()->user()), 403, 'Tidak punya akses');

        $isMember = \App\Models\Organization::find($event->id_org)
            ->members()
            ->where('id_user', $request->id_user)
            ->exists();

        if (!$isMember) {
            abort(403, 'User bukan anggota organization ini');
        }

        $exists = \App\Models\EventCommittee::where('id_event', $id)
            ->where('id_user', $request->id_user)
            ->exists();

        if ($exists) {
            return back()->with('error', 'User sudah ada di event');
        }

        \App\Models\EventCommittee::create([
            'id_event' => $id,
            'id_user' => $request->id_user,
            'id_divisi' => $request->id_divisi,
            'jabatan' => $request->jabatan,
        ]);

        return back()->with('success', 'Member berhasil ditambahkan');
    }

    public function assignMembersBulk(Request $request, $id)
    {
        $request->validate([
            'id_users' => 'required|array|min:1',
            'id_users.*' => 'required|exists:users,id_user',
            'id_divisi' => 'required|exists:divisions,id_divisi',
            'jabatan' => 'required|string|max:100',
        ]);

        $event = Event::visibleTo(auth()->user())->findOrFail($id);
        abort_unless($event->canManageBy(auth()->user()), 403, 'Tidak punya akses');

        $selectedUserIds = collect($request->id_users)->unique()->values();
        $added = [];
        $already = [];
        $notMember = [];

        foreach ($selectedUserIds as $userId) {
            $isMember = $event->organization
                ->members()
                ->where('users.id_user', $userId)
                ->exists();

            if (!$isMember) {
                $notMember[] = $userId;
                continue;
            }

            $exists = EventCommittee::where('id_event', $event->id_event)
                ->where('id_user', $userId)
                ->exists();

            if ($exists) {
                $already[] = $userId;
                continue;
            }

            EventCommittee::create([
                'id_event' => $event->id_event,
                'id_user' => $userId,
                'id_divisi' => $request->id_divisi,
                'jabatan' => $request->jabatan,
            ]);

            $added[] = $userId;
        }

        if (count($added) === 0) {
            return back()->with('error', 'Tidak ada member yang berhasil ditambahkan.');
        }

        return back()->with('success', 'Berhasil menambahkan ' . count($added) . ' member ke committee.');
    }

    public function removeCommittee($eventId, $committeeId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageCommitteeBy(auth()->user()), 403, 'Tidak punya akses');

        $committee = EventCommittee::where('id_comm', $committeeId)
            ->where('id_event', $eventId)
            ->firstOrFail();

        $committee->delete();

        return back()->with('success', 'Member committee berhasil dihapus');
    }

    public function storeRundown(Request $request, $eventId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageRundownBy(auth()->user()), 403, 'Tidak punya akses');

        $request->validate([
            'day_number' => 'required|integer|min:1',
            'session_group' => 'nullable|string|max:255',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after_or_equal:waktu_mulai',
            'kegiatan' => 'required|string|max:255',
            'assigned_to' => [
                'nullable',
                Rule::exists('event_committees', 'id_comm')->where(function ($query) use ($event) {
                    $query->where('id_event', $event->id_event);
                }),
            ],
        ]);

        EventRundownItem::create([
            'id_event' => $event->id_event,
            'day_number' => $request->day_number,
            'session_group' => $request->session_group,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'kegiatan' => $request->kegiatan,
            'assigned_to' => $request->assigned_to,
        ]);

        return back()->with('success', 'Rundown berhasil ditambahkan');
    }

    public function updateRundown(Request $request, $eventId, $rundownId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageRundownBy(auth()->user()), 403, 'Tidak punya akses');

        $request->validate([
            'day_number' => 'required|integer|min:1',
            'session_group' => 'nullable|string|max:255',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after_or_equal:waktu_mulai',
            'kegiatan' => 'required|string|max:255',
            'assigned_to' => [
                'nullable',
                Rule::exists('event_committees', 'id_comm')->where(function ($query) use ($event) {
                    $query->where('id_event', $event->id_event);
                }),
            ],
        ]);

        $rundown = EventRundownItem::where('id_rundown', $rundownId)
            ->where('id_event', $eventId)
            ->firstOrFail();

        $rundown->update([
            'day_number' => $request->day_number,
            'session_group' => $request->session_group,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'kegiatan' => $request->kegiatan,
            'assigned_to' => $request->assigned_to,
        ]);

        return back()->with('success', 'Rundown berhasil diupdate');
    }

    public function destroyRundown($eventId, $rundownId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageRundownBy(auth()->user()), 403, 'Tidak punya akses');

        $rundown = EventRundownItem::where('id_rundown', $rundownId)
            ->where('id_event', $eventId)
            ->firstOrFail();

        $rundown->delete();

        return back()->with('success', 'Rundown berhasil dihapus');
    }

    public function storePartner(Request $request, $eventId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManagePartnerBy(auth()->user()), 403, 'Tidak punya akses');

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'nama_partner' => 'required|string|max:255',
            'jenis_partner' => ['required', Rule::in(['sponsor', 'medpar', 'comrel'])],
            'assigned_pic' => ['nullable', Rule::exists('users', 'id_user')],
            'status' => ['required', Rule::in(['approach', 'prospect', 'contacted', 'follow_up', 'negotiation', 'deal', 'rejected', 'cancelled'])],
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('events.show', $eventId)
                ->withErrors($validator)
                ->withInput()
                ->with('open_tab', 'partners')
                ->with('open_modal', 'partnerModal');
        }

        Partner::create([
            'id_event' => $event->id_event,
            'nama_partner' => $request->nama_partner,
            'jenis_partner' => $request->jenis_partner,
            'assigned_pic' => $request->assigned_pic,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Partner berhasil ditambahkan');
    }

    public function updatePartner(Request $request, $eventId, $partnerId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManagePartnerBy(auth()->user()), 403, 'Tidak punya akses');

        $request->validate([
            'nama_partner' => 'required|string|max:255',
            'jenis_partner' => ['required', Rule::in(['sponsor', 'medpar', 'comrel'])],
            'assigned_pic' => ['nullable', Rule::exists('users', 'id_user')],
            'status' => ['required', Rule::in(['approach', 'prospect', 'contacted', 'follow_up', 'negotiation', 'deal', 'rejected', 'cancelled'])],
            'notes' => 'nullable|string',
        ]);

        $partner = Partner::where('id_partner', $partnerId)
            ->where('id_event', $eventId)
            ->firstOrFail();

        $partner->update([
            'nama_partner' => $request->nama_partner,
            'jenis_partner' => $request->jenis_partner,
            'assigned_pic' => $request->assigned_pic,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Partner berhasil diupdate');
    }

    public function destroyPartner($eventId, $partnerId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManagePartnerBy(auth()->user()), 403, 'Tidak punya akses');

        $partner = Partner::where('id_partner', $partnerId)
            ->where('id_event', $eventId)
            ->firstOrFail();

        $partner->delete();

        return back()->with('success', 'Partner berhasil dihapus');
    }

    public function storeCertificate(Request $request, $eventId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageCertificateBy(auth()->user()), 403, 'Tidak punya akses');

        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'email_penerima' => 'required|email|max:255',
            'nrp_penerima' => 'nullable|string|max:50',
            'file_url' => 'nullable|string|max:2048',
        ]);

        Certificate::create([
            'id_event' => $event->id_event,
            'nama_penerima' => $request->nama_penerima,
            'email_penerima' => $request->email_penerima,
            'nrp_penerima' => $request->nrp_penerima,
            'qr_token' => (string) Str::uuid(),
            'file_url' => $request->file_url,
        ]);

        return back()->with('success', 'Certificate berhasil ditambahkan');
    }

    public function updateCertificate(Request $request, $eventId, $certId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageCertificateBy(auth()->user()), 403, 'Tidak punya akses');

        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'email_penerima' => 'required|email|max:255',
            'nrp_penerima' => 'nullable|string|max:50',
            'file_url' => 'nullable|string|max:2048',
        ]);

        $cert = Certificate::where('id_cert', $certId)
            ->where('id_event', $eventId)
            ->firstOrFail();

        $cert->update([
            'nama_penerima' => $request->nama_penerima,
            'email_penerima' => $request->email_penerima,
            'nrp_penerima' => $request->nrp_penerima,
            'file_url' => $request->file_url,
        ]);

        return back()->with('success', 'Certificate berhasil diupdate');
    }

    public function destroyCertificate($eventId, $certId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageCertificateBy(auth()->user()), 403, 'Tidak punya akses');

        $cert = Certificate::where('id_cert', $certId)
            ->where('id_event', $eventId)
            ->firstOrFail();

        $cert->delete();

        return back()->with('success', 'Certificate berhasil dihapus');
    }

    public function storeDocument(Request $request, $eventId)
    {
        $event = Event::visibleTo(auth()->user())
            ->findOrFail($eventId);

        abort_unless(
            $event->canManageDocumentBy(auth()->user()),
            403
        );

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'document_type' => ['required', \Illuminate\Validation\Rule::in(['proposal', 'lpj', 'invitation_letter', 'mou_partner', 'certificate', 'other'])],
            'title' => 'required|string|max:255',
            'status' => ['required', \Illuminate\Validation\Rule::in(['draft', 'generated', 'final', 'archived', 'failed'])],
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('events.show', $eventId)
                ->withErrors($validator)
                ->withInput()
                ->with('open_tab', 'documents')
                ->with('open_modal', 'documentModal');
        }

        // =========================
        // SAVE PAYLOAD
        // =========================

        $payload = $request->except([
            '_token',
            'title',
            'document_type',
            'status',
            'notes',
        ]);

        // upload logo organisasi
        if ($request->hasFile('organization_logo')) {

            $logoPath = $request
                ->file('organization_logo')
                ->store('document-logos', 'public');

            $payload['organization_logo'] = $logoPath;
        }

        // =========================
        // CREATE DOCUMENT
        // =========================

        $document = \App\Models\GeneratedDocument::create([

            'id_event' =>
                $event->id_event,

            'document_type' =>
                $request->document_type,

            'title' =>
                $request->title,

            'status' =>
                'draft',

            'notes' =>
                $request->notes,

            'snapshot_data' => $payload,

            'generated_by' =>
                auth()->id(),
        ]);

        // =========================
        // GENERATE PDF
        // =========================

        $service = new \App\Services\DocumentService();

        $fileUrl = $service->generate(
            $document->id_document
        );

        // =========================
        // UPDATE FILE URL
        // =========================

        $document->update([
            'file_url' => $fileUrl,
            'status' => 'generated',
            'generated_at' => now(),
        ]);

        return back()->with(
            'success',
            'Document berhasil di-generate'
        );
    }

    public function updateDocument(Request $request, $eventId, $documentId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageDocumentBy(auth()->user()), 403, 'Tidak punya akses');

        $request->validate([
            'document_type' => ['required', Rule::in(['proposal', 'lpj', 'invitation_letter', 'mou_partner', 'certificate', 'other'])],
            'title' => 'required|string|max:255',
            'file_url' => 'nullable|string|max:2048',
            'status' => ['required', Rule::in(['draft', 'generated', 'final', 'archived', 'failed'])],
            'notes' => 'nullable|string',
        ]);

        $document = GeneratedDocument::where('id_document', $documentId)
            ->where('id_event', $eventId)
            ->firstOrFail();

        $document->update([
            'document_type' => $request->document_type,
            'title' => $request->title,
            'file_url' => $request->file_url,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Document berhasil diupdate');
    }

    public function destroyDocument($eventId, $documentId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageDocumentBy(auth()->user()), 403, 'Tidak punya akses');

        $document = GeneratedDocument::where('id_document', $documentId)
            ->where('id_event', $eventId)
            ->firstOrFail();

        $document->delete();

        return back()->with('success', 'Document berhasil dihapus');
    }

    public function destroy($id)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($id);
        abort_unless($event->canManageBy(auth()->user()), 403, 'Tidak punya akses');

        $user = auth()->user();

        $isSuperAdmin = \DB::table('organization_members')
            ->where('organization_id', $event->id_org)
            ->where('user_id', $user->id_user)
            ->where('position', 'admin_org')
            ->exists();

        if (!$isSuperAdmin) {
            abort(403, 'Tidak punya akses');
        }

        $event->delete();

        return redirect('/events')->with('success', 'Event berhasil dihapus');
    }

    public function exportRundown($eventId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);

        abort_unless(
            $event->canManageRundownBy(auth()->user()),
            403,
            'Tidak punya akses'
        );

        $filename = 'rundown_' . $event->id_event . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(
            new RundownExport($eventId),
            $filename
        );
    }

    public function importRundown(Request $request, $eventId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageRundownBy(auth()->user()), 403, 'Tidak punya akses');

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls',
        ]);

        try {
            $import = new RundownImport($eventId);
            Excel::import($import, $request->file('file'));

            $imported = $import->getImported();
            $errors = $import->getErrors();

            if ($imported === 0 && count($errors) > 0) {
                // Jika gagal semua, tampilkan semua error
                $message = "Gagal import! " . count($errors) . " baris gagal: " . implode("; ", array_slice($errors, 0, 5));
                return back()->with('error', $message);
            }

            $message = "Berhasil import $imported rundown";
            if (count($errors) > 0) {
                $message .= ". " . count($errors) . " baris gagal";
                if (count($errors) <= 3) {
                    $message .= ": " . implode("; ", $errors);
                } else {
                    $message .= ": " . implode("; ", array_slice($errors, 0, 3)) . " dan " . (count($errors) - 3) . " lainnya";
                }
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import file: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $filename = 'template_rundown_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new RundownTemplateExport(), $filename);
    }

    // ===========================
    // CERTIFICATE MANAGEMENT
    // ===========================
    public function uploadCertificateTemplate(Request $request, $eventId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageCertificateBy(auth()->user()), 403, 'Tidak punya akses');

        $request->validate([
            'template_file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $path = $request->file('template_file')->store('certificate-templates', 'public');

        return back()->with('success', 'Template certificate berhasil diupload')->with('template_path', $path);
    }

    public function bulkInsertCertificates(Request $request, $eventId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageCertificateBy(auth()->user()), 403, 'Tidak punya akses');

        $request->validate([
            'recipients_file' => 'nullable|file|mimes:csv,xlsx,xls',
            'nama_penerima' => 'nullable|array',
            'email_penerima' => 'nullable|array',
            'nrp_penerima' => 'nullable|array',
        ]);

        $recipients = [];

        // Handle manual input
        if ($request->has('nama_penerima') && count($request->nama_penerima) > 0) {
            $names = $request->input('nama_penerima');
            $emails = $request->input('email_penerima', []);
            $nrp = $request->input('nrp_penerima', []);
            foreach ($names as $index => $name) {
                if (!empty(trim($name))) {
                    $recipients[] = [
                        'nama_penerima' => trim($name),
                        'email_penerima' => trim($emails[$index] ?? ''),
                        'nrp_penerima' => trim($nrp[$index] ?? ''),
                    ];
                }
            }
        }
        // Handle file upload
        elseif ($request->hasFile('recipients_file')) {
            $file = $request->file('recipients_file');
            $filename = $file->getClientOriginalName();

            // Detect file type
            if (in_array($file->getClientOriginalExtension(), ['xlsx', 'xls'])) {
                // Use Laravel Excel for Excel files
                try {
                    $data = Excel::toArray(new CertificateImport(), $file);

                    if (isset($data[0]) && is_array($data[0])) {
                        foreach ($data[0] as $row) {
                            $nama = trim($row['nama_lengkap'] ?? $row['Nama Lengkap'] ?? $row['nama_penerima'] ?? '');
                            $email = trim($row['email_penerima'] ?? $row['Email Penerima'] ?? $row['email'] ?? '');
                            $nrp = trim($row['nrp_penerima'] ?? $row['NRP Penerima'] ?? $row['nrp'] ?? '');
                            if (!empty($nama) && !empty($email)) {
                                $recipients[] = [
                                    'nama_penerima' => $nama,
                                    'email_penerima' => $email,
                                    'nrp_penerima' => $nrp,
                                ];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    return back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
                }
            } else {
                // Manual CSV parsing
                $path = $file->store('temp');
                $fullPath = storage_path('app/' . $path);

                $handle = fopen($fullPath, 'r');
                $header = null;

                while (($row = fgetcsv($handle, 0, ',', '"')) !== false) {
                    if ($header === null) {
                        $header = array_map('trim', $row);
                        continue;
                    }

                    if (count($row) >= 2) {
                        $recipients[] = [
                            'nama_penerima' => trim($row[0] ?? ''),
                            'email_penerima' => trim($row[1] ?? ''),
                            'nrp_penerima' => trim($row[2] ?? ''),
                        ];
                    }
                }
                fclose($handle);
                \Illuminate\Support\Facades\Storage::delete($path);
            }
        }

        $created = 0;
        $errors = [];

        foreach ($recipients as $index => $recipient) {
            try {
                if (empty($recipient['nama_penerima']) || empty($recipient['email_penerima'])) {
                    $errors[] = "Baris " . ($index + 2) . ": Nama atau email kosong";
                    continue;
                }

                // Check if already exists
                $exists = Certificate::where('id_event', $eventId)
                    ->where('email_penerima', $recipient['email_penerima'])
                    ->exists();

                if ($exists) {
                    $errors[] = "Baris " . ($index + 2) . ": Email {$recipient['email_penerima']} sudah ada";
                    continue;
                }

                Certificate::create([
                    'id_event' => $eventId,
                    'nama_penerima' => $recipient['nama_penerima'],
                    'email_penerima' => $recipient['email_penerima'],
                    'nrp_penerima' => $recipient['nrp_penerima'],
                    'qr_token' => (string) Str::uuid(),
                ]);

                $created++;
            } catch (\Exception $e) {
                $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        $message = "Berhasil tambah $created recipient";
        if (count($errors) > 0) {
            $message .= ". " . count($errors) . " error";
        }

        return back()->with('success', $message);
    }

    public function downloadCertificateTemplate()
    {
        $filename = 'template_certificate_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new CertificateTemplateExport(), $filename);
    }

    public function generateCertificates(Request $request, $eventId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageCertificateBy(auth()->user()), 403, 'Tidak punya akses');

        $request->validate([
            'template_path' => 'required|string',
        ]);

        $templatePath = $request->input('template_path');

        // Validate template exists
        if (!Storage::disk('public')->exists($templatePath)) {
            return back()->with('error', 'Template tidak ditemukan');
        }

        $service = new \App\Services\CertificateService();

        // Get configuration from session
        $config = session('certificate_config_' . $eventId);

        // Get certificates without file_url
        $certificates = Certificate::where('id_event', $eventId)
            ->whereNull('file_url')
            ->get();

        $generated = 0;
        $errors = [];

        foreach ($certificates as $cert) {
            try {
                $filePath = $service->generateCertificate(
                    $templatePath,
                    $cert->nama_penerima,
                    $cert->qr_token,
                    $cert->email_penerima,
                    null,
                    $config
                );

                $cert->update(['file_url' => $filePath]);
                $generated++;
            } catch (\Exception $e) {
                $errors[] = $cert->nama_penerima . ": " . $e->getMessage();
            }
        }

        $message = "Berhasil generate $generated certificate";
        if (count($errors) > 0) {
            $message .= ". " . count($errors) . " error";
        }

        return back()->with('success', $message);
    }

    public function saveCertificateConfig(Request $request, $eventId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageCertificateBy(auth()->user()), 403, 'Tidak punya akses');

        try {
            $config = $request->validate([
                'imageWidth' => 'nullable|numeric',
                'imageHeight' => 'nullable|numeric',
                'canvasWidth' => 'nullable|numeric',
                'canvasHeight' => 'nullable|numeric',
                'canvasScale' => 'nullable|numeric',
                'textBoxes' => 'required|array',
                'textBoxes.*.text' => 'string',
                'textBoxes.*.type' => 'nullable|string',
                'textBoxes.*.left' => 'numeric',
                'textBoxes.*.top' => 'numeric',
                'textBoxes.*.fontSize' => 'numeric',
                'textBoxes.*.fontFamily' => 'string',
                'textBoxes.*.fontWeight' => 'string',
                'textBoxes.*.fill' => 'string',
                'textBoxes.*.textAlign' => 'string',
            ]);

            // Store in session
            session(['certificate_config_' . $eventId => $config]);

            \Log::info('Certificate config saved', [
                'eventId' => $eventId,
                'textBoxCount' => count($config['textBoxes']),
                'config' => $config
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Configuration saved successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Certificate config validation failed', [
                'eventId' => $eventId,
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', array_values(array_flatten($e->errors()))),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Certificate config save error', [
                'eventId' => $eventId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error saving configuration: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendCertificates(Request $request, $eventId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageCertificateBy(auth()->user()), 403, 'Tidak punya akses');

        $request->validate([
            'cert_ids' => 'required|array|min:1',
            'cert_ids.*' => 'required|integer|exists:certificates,id_cert',
        ]);

        $certificates = Certificate::whereIn('id_cert', $request->input('cert_ids'))
            ->where('id_event', $eventId)
            ->get();

        $sent = 0;
        $errors = [];

        foreach ($certificates as $cert) {
            try {
                if (empty($cert->file_url)) {
                    $errors[] = $cert->nama_penerima . ": Certificate belum di-generate";
                    continue;
                }

                Mail::to($cert->email_penerima)->send(
                    new \App\Mail\SendCertificate($cert, $event->nama_event)
                );

                $cert->update(['sent_at' => now()]);
                $sent++;
            } catch (\Exception $e) {
                $errors[] = $cert->nama_penerima . ": " . $e->getMessage();
            }
        }

        $message = "Berhasil kirim $sent certificate";
        if (count($errors) > 0) {
            $message .= ". " . count($errors) . " error";
        }

        return back()->with('success', $message);
    }
    public function downloadCertificatesZip(Request $request, $eventId)
    {
        $request->validate([
            'cert_ids' => 'required|array|min:1'
        ]);

        $certificates = Certificate::whereIn(
            'id_cert',
            $request->cert_ids
        )->get();

        // $zipName =
        //     'certificates_' .
        //     now()->format('YmdHis') .
        //     '.zip';

        $firstCert = $certificates->first();

        $eventName =
            $firstCert?->event?->nama_event ??
            'EVENT';

        $safeEventName = preg_replace(
            '/[^A-Za-z0-9]/',
            '_',
            $eventName
        );

        $zipName =
            'certificates_' .
            strtoupper($safeEventName) .
            '.zip';
        $zipPath =
            storage_path('app/temp/' . $zipName);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }

        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {

            foreach ($certificates as $cert) {

                if (!$cert->file_url) {
                    continue;
                }

                $file =
                    storage_path(
                        'app/public/' .
                        $cert->file_url
                    );

                if (file_exists($file)) {

                    $zip->addFile(
                        $file,
                        basename($file)
                    );
                }
            }

            $zip->close();
        }

        return response()
            ->download($zipPath)
            ->deleteFileAfterSend(true);
    }
}
