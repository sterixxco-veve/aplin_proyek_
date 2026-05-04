<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;

class DivisionController extends Controller
{
    /**
     * Menampilkan daftar divisi master.
     */
    public function index()
    {
        $divisions = Division::all();
        return view('divisions.index', compact('divisions'));
    }

    /**
     * Menyimpan divisi master baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:100|unique:divisions,nama_divisi',
        ]);

        Division::create([
            'nama_divisi' => $request->nama_divisi,
            'is_default' => 0, // Default 0 untuk input manual dari web
        ]);

        return back()->with('success', 'Divisi baru berhasil ditambahkan ke master list!');
    }
}