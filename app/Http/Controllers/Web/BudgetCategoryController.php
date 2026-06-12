<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetCategoryController extends Controller
{
    public function index()
    {
        $categories = BudgetCategory::withCount('eventBudgets')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Menyimpan data baru (Murni hanya nama_kategori)
     */
    public function store(Request $request)
    {
        // REVISI: Buang validasi deskripsi
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Simpan ke budget_categories (Hanya nama_kategori)
            BudgetCategory::create([
                'nama_kategori' => $request->nama_kategori,
            ]);

            // 2. Simpan ke expense_categories (Hanya nama_kategori)
            ExpenseCategory::create([
                'nama_kategori' => $request->nama_kategori,
            ]);
        });

        return redirect()->back()->with('success', 'Kategori baru berhasil disinkronkan ke sistem Budget dan Expense!');
    }

    /**
     * Memperbarui data di kedua tabel sekaligus
     */
    public function update(Request $request, $id)
    {
        // REVISI: Buang validasi deskripsi
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request, $id) {
            $budgetCategory = BudgetCategory::findOrFail($id);
            
            // Cari data di ExpenseCategory yang namanya sama SEBELUM diubah
            $expenseCategory = ExpenseCategory::where('nama_kategori', $budgetCategory->nama_kategori)->first();

            // Update BudgetCategory
            $budgetCategory->update([
                'nama_kategori' => $request->nama_kategori,
            ]);

            // Update ExpenseCategory
            if ($expenseCategory) {
                $expenseCategory->update([
                    'nama_kategori' => $request->nama_kategori,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui di kedua sistem keuangan!');
    }

    /**
     * Menghapus data dari kedua tabel
     */
    public function destroy($id)
    {
        $budgetCategory = BudgetCategory::findOrFail($id);
        
        if ($budgetCategory->eventBudgets()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal menghapus! Kategori ini masih digunakan oleh data anggaran event.');
        }

        $expenseCategory = ExpenseCategory::where('nama_kategori', $budgetCategory->nama_kategori)->first();

        if ($expenseCategory && $expenseCategory->expenses()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal menghapus! Kategori ini sudah memiliki data laporan pengeluaran (Expense Report).');
        }

        DB::transaction(function () use ($budgetCategory, $expenseCategory) {
            if ($expenseCategory) {
                $expenseCategory->delete();
            }
            $budgetCategory->delete();
        });

        return redirect()->back()->with('success', 'Kategori berhasil dibersihkan dari kedua sistem keuangan!');
    }
}