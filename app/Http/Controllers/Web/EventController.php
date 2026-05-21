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
use Illuminate\Validation\Rule;

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
        $expenses = ExpenseReport::where('id_event', $id)->get();
        
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
            'expenses'
        ));
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

    public function index()
    {
        $events = \App\Models\Event::visibleTo(auth()->user())->latest()->get();
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
        $event = Event::visibleTo(auth()->user())->findOrFail($id);
        abort_unless($event->canManageBy(auth()->user()), 403, 'Tidak punya akses');

        $request->validate([
            'nama_event' => 'required',
            'id_org' => 'required',
            'id_event_category' => 'required|exists:event_categories,id_event_category',
            'tgl_mulai' => 'nullable|date'
        ]);

        $event->update([
            'nama_event' => $request->nama_event,
            'id_org' => $request->id_org,
            'id_event_category' => $request->id_event_category,
            'tgl_mulai' => $request->tgl_mulai
        ]);

        return redirect('/events/' . $event->id_event)
            ->with('success', 'Event updated');
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

        \App\Models\EventCommittee::create([
            'id_event' => $event->id_event,
            'id_user' => auth()->user()->id_user,
            'id_divisi' => 1,
            'jabatan' => 'Ketua Acara',
        ]);

        return redirect('/events/' . $event->id_event);
    }

    public function assignMember(Request $request, $id)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'id_divisi' => 'required|exists:divisions,id_divisi',
            'jabatan' => 'required|string|max:100',
        ]);

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

        $request->validate([
            'nama_partner' => 'required|string|max:255',
            'jenis_partner' => ['required', Rule::in(['sponsor', 'medpar', 'comrel'])],
            'assigned_pic' => ['nullable', Rule::exists('users', 'id_user')],
            'status' => ['required', Rule::in(['approach', 'prospect', 'contacted', 'follow_up', 'negotiation', 'deal', 'rejected', 'cancelled'])],
            'notes' => 'nullable|string',
        ]);

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
            'file_url' => 'nullable|string|max:2048',
        ]);

        Certificate::create([
            'id_event' => $event->id_event,
            'nama_penerima' => $request->nama_penerima,
            'email_penerima' => $request->email_penerima,
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
            'file_url' => 'nullable|string|max:2048',
        ]);

        $cert = Certificate::where('id_cert', $certId)
            ->where('id_event', $eventId)
            ->firstOrFail();

        $cert->update([
            'nama_penerima' => $request->nama_penerima,
            'email_penerima' => $request->email_penerima,
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
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageDocumentBy(auth()->user()), 403, 'Tidak punya akses');

        $request->validate([
            'document_type' => ['required', Rule::in(['proposal', 'lpj', 'invitation_letter', 'mou_partner', 'certificate', 'other'])],
            'title' => 'required|string|max:255',
            'file_url' => 'nullable|string|max:2048',
            'status' => ['required', Rule::in(['draft', 'generated', 'final', 'archived', 'failed'])],
            'notes' => 'nullable|string',
        ]);

        GeneratedDocument::create([
            'id_event' => $event->id_event,
            'document_type' => $request->document_type,
            'title' => $request->title,
            'file_url' => $request->file_url,
            'status' => $request->status,
            'generated_by' => auth()->user()->id_user,
            'generated_at' => now(),
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Document berhasil ditambahkan');
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
            ->where('role', 'admin_org')
            ->exists();

        if (!$isSuperAdmin) {
            abort(403, 'Tidak punya akses');
        }

        $event->delete();

        return redirect('/events')->with('success', 'Event berhasil dihapus');
    }
}