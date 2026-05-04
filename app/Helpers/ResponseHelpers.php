class ResponseHelper {
    public static function success($data) {
        return response()->json(['data'=>$data],200);
    }

    private function isLeader($user, $eventId)
{
    return $user->committees()
        ->where('id_event', $eventId)
        ->where('jabatan', 'leader')
        ->exists();
}
}