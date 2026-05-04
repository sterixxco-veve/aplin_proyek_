<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Organization;

class CheckOrgRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // 🔑 Ambil ID organization dari route
        $orgId = $request->route('id');

        $org = Organization::findOrFail($orgId);

        $userId = auth()->user()->id_user;

        // ❗ cek apakah user punya role yang sesuai
        if (!$org->hasRole($userId, $role)) {
            abort(403, 'Kamu tidak punya akses');
        }


        return $next($request);
    }
}