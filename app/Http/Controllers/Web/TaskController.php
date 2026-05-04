<?php

namespace App\Http\Controllers\Web;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
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

        $event = Event::findOrFail($task->id_event);

        // 🔥 SUPER ADMIN CHECK (organization level)
        $isSuperAdmin = DB::table('organization_members')
            ->where('organization_id', $event->id_org)
            ->where('user_id', $user->id_user)
            ->where('role', 'super_admin')
            ->exists();

        // 🔥 COMMITTEE CHECK (event level)
        $isCommittee = EventCommittee::where('id_event', $event->id_event)
            ->where('id_user', $user->id_user)
            ->exists();

        if (!$isCommittee && !$isSuperAdmin) {
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
            'deadline' => 'nullable|date'
        ]);

        $user = auth()->user();
        $event = Event::findOrFail($eventId);

        // 🔥 cek apakah user bagian dari event
        $isCommittee = EventCommittee::where('id_event', $eventId)
            ->where('id_user', $user->id_user)
            ->exists();

        if (!$isCommittee) {
            abort(403, 'Bukan panitia event');
        }

        Task::create([
            'id_event' => $eventId,
            'nama_tugas' => $request->nama_tugas,
            'id_divisi' => $request->id_divisi,
            'assigned_to' => $request->assigned_to,
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

        if (!$isCommittee) {
            abort(403);
        }

        $task->update($request->only([
            'nama_tugas',
            'id_divisi',
            'assigned_to',
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

        if (!$isCommittee) {
            abort(403);
        }

        $task->delete();

        return response()->json(['success' => true]);
    }


    // =========================
    // KANBAN VIEW
    // =========================
    public function index($id)
    {
        $event = Event::findOrFail($id);

        $tasks = Task::where('id_event', $id)->get();

        return view('tasks.kanban', compact('tasks', 'event'));
    }
}