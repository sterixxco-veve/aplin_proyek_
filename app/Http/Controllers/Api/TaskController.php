<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\EventCommittee;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['division', 'assignee']);

        if ($request->id_event) {
            $query->where('id_event', $request->id_event);
        }

        return TaskResource::collection($query->get());
    }

    public function store(StoreTaskRequest $request)
    {
        $user = auth()->user();

        // 🔥 COMBINE RBAC LOGIC
        if (
            !$user->isSuperAdmin() &&
            !$user->isEventLeader($request->id_event)
        ) {
            return response()->json([
                'message' => 'Only leader or admin can create task'
            ], 403);
        }

        $committee = EventCommittee::where('id_event', $request->id_event)
            ->where('id_divisi', $request->id_divisi)
            ->first();

        $task = Task::create([
            ...$request->validated(),
            'assigned_to' => $committee?->id_user
        ]);

        return new TaskResource($task);
    }

    public function show($id)
    {
        return new TaskResource(
            Task::with(['division', 'assignee'])->findOrFail($id)
        );
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = auth()->user();

        // 🔥 RBAC: hanya assignee atau leader/admin
        if (
            $task->assigned_to !== $user->id_user &&
            !$user->isSuperAdmin() &&
            !$user->isEventLeader($task->id_event)
        ) {
            return response()->json([
                'message' => 'Not allowed to update this task'
            ], 403);
        }

        $task->update($request->validated());

        return new TaskResource($task);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $user = auth()->user();

        // 🔥 hanya admin / leader
        if (
            !$user->isSuperAdmin() &&
            !$user->isEventLeader($task->id_event)
        ) {
            return response()->json([
                'message' => 'Not allowed to delete this task'
            ], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function kanban($eventId)
    {
        $tasks = Task::where('id_event', $eventId)
            ->get()
            ->groupBy('status');

        return response()->json([
            'todo' => TaskResource::collection($tasks['todo'] ?? []),
            'progress' => TaskResource::collection($tasks['progress'] ?? []),
            'done' => TaskResource::collection($tasks['done'] ?? []),
        ]);
    }
}