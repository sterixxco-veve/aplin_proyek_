<?php

namespace App\Http\Controllers\Web;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Division;
use App\Models\EventCommittee;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    // =========================
    // UPDATE STATUS (KANBAN)
    // =========================
    
    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = auth()->user();

        $request->validate([
            'status' => 'required|in:todo,progress,done',
        ]);

        $event = Event::findOrFail($task->id_event);

        // 🔥 SUPER ADMIN CHECK (organization level)
        $isSuperAdmin = DB::table('organization_members')
            ->where('organization_id', $event->id_org)
            ->where('user_id', $user->id_user)
            ->where('position', 'admin_org')
            ->exists();

        // 🔥 COMMITTEE CHECK (event level)
        $isCommittee = EventCommittee::where('id_event', $event->id_event)
            ->where('id_user', $user->id_user)
            ->exists();

        if (!$isCommittee && !$isSuperAdmin && !$event->isVisibleTo($user)) {
            abort(403, 'Tidak punya akses');
        }

        $task->update([
            'status' => $request->status
        ]);

        return response()->json(['success' => true]);
    }


    // =========================
    // CREATE TASK
    // =========================
    public function store(Request $request, $eventId)
    {
        $request->validate([
            'nama_tugas' => 'required|string|max:255',
            'id_divisi' => 'required|exists:divisions,id_divisi',
            'assigned_to' => 'nullable|exists:users,id_user',
            'brief' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date'
        ]);

        $user = auth()->user();
        $event = Event::findOrFail($eventId);

        // 🔥 cek apakah user bagian dari event
        $isCommittee = EventCommittee::where('id_event', $eventId)
            ->where('id_user', $user->id_user)
            ->exists();

        if (!$isCommittee && !$event->isVisibleTo($user)) {
            abort(403, 'Bukan panitia event');
        }

        Task::create([
            'id_event' => $eventId,
            'nama_tugas' => $request->nama_tugas,
            'id_divisi' => $request->id_divisi,
            'brief' => $request->brief,
            'assigned_to' => $request->assigned_to,
            'priority' => $request->priority,
            'status' => 'todo',
            'deadline' => $request->deadline
        ]);

        return back()->with('success', 'Task berhasil dibuat');
    }

    public function show($id)
    {
        $task = Task::with('assignee', 'division')->findOrFail($id);

        return response()->json($task);
    }

    // =========================
    // UPDATE TASK
    // =========================
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = auth()->user();

        $event = Event::findOrFail($task->id_event);

        $isCommittee = EventCommittee::where('id_event', $event->id_event)
            ->where('id_user', $user->id_user)
            ->exists();

        if (!$isCommittee && !$event->isVisibleTo($user)) {
            abort(403);
        }

        $request->validate([
            'nama_tugas' => 'sometimes|string|max:255',
            'brief' => 'nullable|string',
            'id_divisi' => 'sometimes|exists:divisions,id_divisi',
            'assigned_to' => 'nullable|exists:users,id_user',
            'priority' => 'sometimes|in:low,medium,high',
            'deadline' => 'nullable|date',
        ]);

        $task->update($request->only([
            'nama_tugas',
            'brief',
            'id_divisi',
            'assigned_to',
            'priority',
            'deadline'
        ]));

        return response()->json([
            'success' => true,
            'task' => $task->load('assignee')
        ]);
    }


    // =========================
    // DELETE TASK
    // =========================
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $user = auth()->user();

        $event = Event::findOrFail($task->id_event);

        $isCommittee = EventCommittee::where('id_event', $event->id_event)
            ->where('id_user', $user->id_user)
            ->exists();

        if (!$isCommittee && !$event->isVisibleTo($user)) {
            abort(403);
        }

        $task->delete();

        return response()->json(['success' => true]);
    }


    // =========================
    // KANBAN VIEW
    // =========================
    public function index($eventId)
    {
        $event = Event::visibleTo(auth()->user())->with('organization.members')->findOrFail($eventId);

        $tasks = Task::with(['assignee', 'division'])
            ->where('id_event', $eventId)
            ->get();
        $divisions = Division::orderBy('nama_divisi')->get();
        $members = $event->organization?->members ?? collect();
        $user = auth()->user();

        $canManageTasks = DB::table('organization_members')
            ->where('organization_id', $event->id_org)
            ->where('user_id', $user->id_user)
            ->where('position', 'admin_org')
            ->exists()
            || EventCommittee::where('id_event', $eventId)
                ->where('id_user', $user->id_user)
                ->exists();

        return view('tasks.task', compact('tasks', 'event', 'divisions', 'members', 'canManageTasks'));
    }

    public function listEvent()
    {
        $user = auth()->user();

        // ambil event yang user ikut
        $events = Event::visibleTo($user)->latest()->get();

        return view('tasks.index', compact('events'));
    }
}