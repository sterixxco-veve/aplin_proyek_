class TaskService
{
    public function moveTask($taskId, $newStatus)
    {
        $task = Task::findOrFail($taskId);

        if (!in_array($newStatus, ['todo','progress','done'])) {
            throw new \Exception('Invalid status');
        }

        $task->status = $newStatus;
        $task->save();

        return $task;
    }

    public function getBoard($eventId)
    {
        return [
            'todo' => Task::where('id_event',$eventId)->where('status','todo')->get(),
            'progress' => Task::where('id_event',$eventId)->where('status','progress')->get(),
            'done' => Task::where('id_event',$eventId)->where('status','done')->get(),
        ];
    }
}