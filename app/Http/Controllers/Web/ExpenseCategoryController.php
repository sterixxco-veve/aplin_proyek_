<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::all();

        return view('expense_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:expense_categories,nama_kategori'
        ]);

        ExpenseCategory::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return back();
    }

    public function destroy($id)
    {
        $category = ExpenseCategory::findOrFail($id);

        // 🔥 cegah delete kalau masih dipakai
        if ($category->expenses()->count() > 0) {
            return back()->with('error', 'Kategori masih dipakai');
        }

        $category->delete();

        return back();
    }
}