<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function show($id)
    {
        $org = auth()->user()
            ->organizations()
            ->with('members')
            ->where('id_org', $id)
            ->firstOrFail();

        return view('organizations.show', compact('org'));
    }

    public function invite(Request $request, $orgId)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $org = Organization::findOrFail($orgId);

        // ❗ cek role dulu
        if (!$org->hasRole(auth()->user()->id_user, 'admin_org')) {
            abort(403, 'Kamu tidak punya akses');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan');
        }

        $org->members()->syncWithoutDetaching([
            $user->id_user => ['role' => 'member']
        ]);

        return back()->with('success', 'User berhasil di-invite');
    }

    public function index()
    {
        $orgs = auth()->user()->organizations;
        return view('organizations.index', compact('orgs'));
    }
 
    public function create()
    {
        return view('organizations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_org' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $org = Organization::create([
            'nama_org' => $request->nama_org,
            'logo_path' => $logoPath,
        ]);

        $org->members()->attach(auth()->user()->id_user, [
            'role' => 'admin_org'
        ]);

        return redirect('/organizations')->with('success', 'Organization created');
    }
}