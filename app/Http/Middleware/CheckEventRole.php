class CheckEventRole
{
    public function handle($request, Closure $next, $role)
    {
        $user = auth()->user();
        $eventId = $request->route('event');

        $hasRole = EventMemberRole::where([
            'id_user' => $user->id_user,
            'id_event' => $eventId,
            'role_event' => $role
        ])->exists();

        if (!$hasRole) {
            return response()->json(['message'=>'Forbidden'],403);
        }

        return $next($request);
    }
}