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

    public function inviteBulk(Request $request, $orgId)
    {
        $request->validate([
            'emails' => 'required|string',
            'role' => 'required|in:admin_org,member',
        ]);

        $org = Organization::findOrFail($orgId);

        if (!$org->hasRole(auth()->user()->id_user, 'admin_org')) {
            abort(403, 'Kamu tidak punya akses');
        }

        $emails = collect(preg_split('/[\r\n,;]+/', $request->emails))
            ->map(fn ($email) => trim($email))
            ->filter()
            ->unique()
            ->values();

        if ($emails->isEmpty()) {
            return back()->with('error', 'Masukkan minimal satu email yang valid.');
        }

        $added = [];
        $already = [];
        $notFound = [];

        foreach ($emails as $email) {
            $user = User::where('email', $email)->first();

            if (!$user) {
                $notFound[] = $email;
                continue;
            }

            $exists = $org->members()->where('users.id_user', $user->id_user)->exists();

            if ($exists) {
                $already[] = $email;
                continue;
            }

            $org->members()->attach($user->id_user, [
                'role' => $request->role,
            ]);

            $added[] = $email;
        }

        if (count($added) === 0) {
            return back()->with('error', 'Tidak ada user yang berhasil ditambahkan. Sudah member: ' . implode(', ', $already) . '. Tidak ditemukan: ' . implode(', ', $notFound));
        }

        $message = 'Berhasil menambahkan ' . count($added) . ' user.';

        if (!empty($already)) {
            $message .= ' Sudah member: ' . implode(', ', $already) . '.';
        }

        if (!empty($notFound)) {
            $message .= ' Tidak ditemukan: ' . implode(', ', $notFound) . '.';
        }

        return back()->with('success', $message);
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

    public function edit($id)
    {
        $org = auth()->user()
            ->organizations()
            ->where('id_org', $id)
            ->firstOrFail();

        // Keamanan: Cek apakah user adalah admin organisasi
        if (!$org->hasRole(auth()->user()->id_user, 'admin_org')) {
            abort(403, 'Hanya admin organisasi yang dapat mengubah data.');
        }

        return view('organizations.edit', compact('org'));
    }

    public function update(Request $request, $id)
    {
        $org = Organization::findOrFail($id);

        // Keamanan: Pastikan yang update adalah admin organisasi tersebut
        if (!$org->hasRole(auth()->user()->id_user, 'admin_org')) {
            abort(403);
        }

        $request->validate([
            'nama_org' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        
        $org->nama_org = $request->nama_org;

        if ($request->hasFile('logo')) {
           
            if ($org->logo_path) {
                Storage::disk('public')->delete($org->logo_path);
            }
            
            $org->logo_path = $request->file('logo')->store('logos', 'public');
        }

        $org->save();

        return redirect()->route('organizations.show', $id)
            ->with('success', 'Informasi organisasi berhasil diperbarui!');
    }

}