<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{
    /**
     * Tampilkan detail organisasi dengan daftar divisi dinamis
     */
    public function show($id)
    {
        // Load data organisasi beserta member-nya
        $org = auth()->user()
            ->organizations()
            ->with(['members' => function ($query) {
                // Pastikan kita mengambil pivot id_divisi agar bisa ditampilkan di UI
                $query->withPivot('id_divisi');
            }])
            ->where('id_org', $id)
            ->firstOrFail();

        // Ambil semua master divisi untuk dijadikan pilihan dropdown
        $divisions = Division::all();

        return view('organizations.show', compact('org', 'divisions'));
    }

    /**
     * Undang Anggota Baru (Single Invite)
     */
    public function invite(Request $request, $orgId)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $org = Organization::findOrFail($orgId);

        // Keamanan: Cek apakah user pengundang adalah bagian dari BPH (id_divisi = 1) di organisasi ini
        $isAdmin = $org->members()
            ->where('users.id_user', auth()->user()->id_user)
            ->wherePivot('id_divisi', 1) // 1 adalah ID Divisi BPH (Admin)
            ->exists();

        if (!$isAdmin) {
            abort(403, 'Kamu tidak punya akses untuk mengundang anggota.');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        // Cari divisi default (misal divisi 'Acara' atau default lainnya)
        $defaultDivision = Division::where('is_default', 1)->first() ?? Division::first();
        $divisiId = $defaultDivision ? $defaultDivision->id_divisi : null;

        $org->members()->syncWithoutDetaching([
            $user->id_user => ['id_divisi' => $divisiId]
        ]);

        // Sinkronisasi global di tabel users
        $user->id_divisi = $divisiId;
        $user->save();

        return back()->with('success', 'User berhasil di-invite ke organisasi.');
    }

    /**
     * Undang Banyak Anggota (Bulk Invite dengan Dropdown Divisi)
     */
    public function inviteBulk(Request $request, $orgId)
    {
        $request->validate([
            'emails' => 'required|string',
            'id_divisi' => 'required|exists:divisions,id_divisi',
        ]);

        $org = Organization::findOrFail($orgId);

        // Keamanan: Cek apakah user pengundang adalah bagian dari BPH (id_divisi = 1)
        $isAdmin = $org->members()
            ->where('users.id_user', auth()->user()->id_user)
            ->wherePivot('id_divisi', 1)
            ->exists();

        if (!$isAdmin) {
            abort(403, 'Kamu tidak punya akses untuk melakukan bulk invite.');
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

            // Simpan ke pivot table dengan id_divisi pilihan
            $org->members()->attach($user->id_user, [
                'id_divisi' => $request->id_divisi,
            ]);

            // Sinkronisasi global ke tabel users
            $user->id_divisi = $request->id_divisi;
            $user->save();

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

    /**
     * Update Divisi/Role Anggota via Dropdown Instan
     */
    public function updateMemberRole(Request $request, $id_org, $id_user)
    {
        $request->validate([
            'id_divisi' => 'required|exists:divisions,id_divisi',
        ]);

        $org = Organization::findOrFail($id_org);

        // Keamanan: Pastikan yang mengubah adalah BPH (id_divisi = 1)
        $isAdmin = $org->members()
            ->where('users.id_user', auth()->user()->id_user)
            ->wherePivot('id_divisi', 1)
            ->exists();

        if (!$isAdmin) {
            abort(403, 'Anda tidak memiliki hak untuk memperbarui peran anggota.');
        }

        // 1. Update kolom id_divisi di tabel pivot organization_members
        $org->members()->updateExistingPivot($id_user, [
            'id_divisi' => $request->id_divisi
        ]);

        // 2. SINKRONISASI GLOBAL: Update id_divisi di tabel users
        $user = User::findOrFail($id_user);
        $user->id_divisi = $request->id_divisi;
        $user->save();

        return redirect()->back()->with('success', 'Divisi dan Role anggota berhasil disinkronkan!');
    }

    /**
     * Hapus Anggota dari Organisasi
     */
    public function removeMember($id_org, $id_user)
    {
        $org = Organization::findOrFail($id_org);

        // Keamanan: Cek hak akses BPH
        $isAdmin = $org->members()
            ->where('users.id_user', auth()->user()->id_user)
            ->wherePivot('id_divisi', 1)
            ->exists();

        if (!$isAdmin) {
            abort(403, 'Akses ditolak.');
        }

        $org->members()->detach($id_user);

        return redirect()->back()->with('success', 'Anggota berhasil dihapus dari organisasi.');
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

        // Pembuat organisasi otomatis diletakkan di BPH (id_divisi = 1) sebagai Admin
        $org->members()->attach(auth()->user()->id_user, [
            'id_divisi' => 1
        ]);

        return redirect('/organizations')->with('success', 'Organization created');
    }

    public function edit($id)
    {
        $org = auth()->user()
            ->organizations()
            ->where('id_org', $id)
            ->firstOrFail();

        // Keamanan: Cek apakah user pengedit adalah BPH (id_divisi = 1)
        $isAdmin = $org->members()
            ->where('users.id_user', auth()->user()->id_user)
            ->wherePivot('id_divisi', 1)
            ->exists();

        if (!$isAdmin) {
            abort(403, 'Hanya divisi BPH yang dapat mengubah data.');
        }

        return view('organizations.edit', compact('org'));
    }

    public function update(Request $request, $id)
    {
        $org = Organization::findOrFail($id);

        // Keamanan: Cek hak akses pengedit
        $isAdmin = $org->members()
            ->where('users.id_user', auth()->user()->id_user)
            ->wherePivot('id_divisi', 1)
            ->exists();

        if (!$isAdmin) {
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