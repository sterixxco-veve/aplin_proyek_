class OrganizationService
{
    public function invite($orgId, $email, $role, $inviter)
    {
        $user = User::where('email',$email)->first();

        if (!$user) {
            throw new \Exception('User not found');
        }

        $exists = OrganizationMember::where([
            'id_org'=>$orgId,
            'id_user'=>$user->id_user
        ])->exists();

        if ($exists) {
            throw new \Exception('Already member');
        }

        return OrganizationMember::create([
            'id_org'=>$orgId,
            'id_user'=>$user->id_user,
            'role_org'=>$role,
            'status'=>'aktif',
            'joined_at'=>now()
        ]);
    }
}