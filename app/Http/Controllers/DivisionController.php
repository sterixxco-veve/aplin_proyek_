<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use Illuminate\Validation\Rule;

class DivisionController extends Controller
{
  
    public function index()
    {
        $divisions = Division::orderBy('created_at', 'desc')->get();
        return view('divisions.index', compact('divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:100|unique:divisions,nama_divisi',
        ], [
            'nama_divisi.unique' => 'Nama divisi ini sudah ada di dalam sistem.',
        ]);

        Division::create([
            'nama_divisi' => $request->nama_divisi,
            'is_default' => 0,
        ]);

        return back()->with('success', 'Divisi baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $division = Division::findOrFail($id);

        if ($division->is_default) {
            return back()->with('error', 'Nama divisi sistem bawaan tidak boleh diubah!');
        }

        $request->validate([
           
            'nama_divisi' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('divisions')->ignore($division->id_divisi, 'id_divisi')
            ],
        ]);

        $division->update([
            'nama_divisi' => $request->nama_divisi
        ]);

        return back()->with('success', 'Nama divisi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $division = Division::findOrFail($id);
        
        if ($division->is_default) {
            return back()->with('error', 'Divisi sistem bawaan tidak boleh dihapus!');
        }

        $division->delete();

        return back()->with('success', 'Divisi berhasil dihapus dari sistem.');
    }
}