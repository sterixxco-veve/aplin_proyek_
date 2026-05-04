<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ExpenseReport;

class EventController extends Controller
{
    // =========================
    // SHOW EVENT + KANBAN
    // =========================
    public function show($id)
    {
        $event = \App\Models\Event::with([
            'committees.user',
            'organization.members'
        ])->findOrFail($id);

        $divisions = \App\Models\Division::all();
        $members = $event->organization->members;

        // 🔹 ambil tasks (INI  YANG KAMU KURANG)
        $tasks = Task::with('assignee')->where('id_event', $id)->get();
        $expenses = ExpenseReport::where('id_event', $id)->get();
        // 🔹 progress
        $total = $tasks->count();
        $done = $tasks->where('status', 'done')->count();

        $progress = $total > 0 ? round(($done / $total) * 100) : 0;

        return view('events.show', compact(
            'event',
            'tasks',
            'divisions',
            'members',
            'progress',
            'expenses' // 🔥 INI YANG KAMU BELUM ADA
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

        return view('events.create', compact('organizations'));
    }

    public function index()
    {
        $events = \App\Models\Event::all();
        return view('events.index', compact('events'));
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $organizations = auth()->user()->organizations;

        return view('events.edit', compact('event', 'organizations'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'nama_event' => 'required',
            'id_org' => 'required',
            'kategori' => 'required',
            'tgl_mulai' => 'nullable|date'
        ]);

        $event->update([
            'nama_event' => $request->nama_event,
            'id_org' => $request->id_org,
            'kategori' => $request->kategori,
            'tgl_mulai' => $request->tgl_mulai
        ]);

        return redirect('/events/' . $event->id_event)
            ->with('success', 'Event updated');
    }
    public function store(Request $request)
    {
        $request->validate([
           'nama_event' => 'required|unique:events,nama_event', 
            'id_org' => 'required|exists:organizations,id_org',
        ]);

        // ❗ pastikan user memang member org ini
        $isMember = auth()->user()->organizations
            ->contains('id_org', $request->id_org);

        if (!$isMember) {
            abort(403, 'Bukan anggota organization ini');
        }

        $event = \App\Models\Event::create([
            'id_event' => Str::uuid(),
            'nama_event' => $request->nama_event,
            'id_org' => $request->id_org,
        ]);

        // 🔥 auto jadi committee (owner)
        \App\Models\EventCommittee::create([
            'id_event' => $event->id_event,
            'id_user' => auth()->user()->id_user,
            'id_divisi' => 1, // sementara (nanti kita benerin)
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

        $event = \App\Models\Event::findOrFail($id);

        // ❗ pastikan user yg di-assign memang dari organization event
        $isMember = \App\Models\Organization::find($event->id_org)
            ->members()
            ->where('id_user', $request->id_user)
            ->exists();

        if (!$isMember) {
            abort(403, 'User bukan anggota organization ini');
        }

        // ❗ cegah duplicate
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
    public function destroy($id)
{
    $event = Event::findOrFail($id);

    $user = auth()->user();

    // 🔥 hanya super admin yang boleh delete
    $isSuperAdmin = \DB::table('organization_members')
        ->where('organization_id', $event->id_org)
        ->where('user_id', $user->id_user)
        ->where('role', 'super_admin')
        ->exists();

    if (!$isSuperAdmin) {
        abort(403, 'Tidak punya akses');
    }

    $event->delete();

    return redirect('/events')->with('success', 'Event berhasil dihapus');
}
}   