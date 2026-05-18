<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\ExpenseReport;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function home()
    {
        $events = auth()->user()
            ->organizations()
            ->with('events')
            ->get()
            ->flatMap(fn ($organization) => $organization->events)
            ->sortBy('tgl_mulai')
            ->values();

        return view('finance', compact('events'));
    }

    public function index($eventId)
    {
        return ExpenseReport::with(['category', 'user'])
            ->where('id_event', $eventId)
            ->get();
    }

    public function page($eventId)
    {
        $event = Event::findOrFail($eventId);
        $summary = $event->financial_summary;
        $expenses = ExpenseReport::with(['category', 'user'])
            ->where('id_event', $eventId)
            ->latest('id_expense')
            ->get();
        $categories = ExpenseCategory::all();

        return view('events.finance', compact(
            'event',
            'expenses',
            'eventId',
            'summary',
            'categories' // 🔥 WAJIB
        ));
    }

    public function store(Request $request, $eventId)
    {
        $request->validate([
            'nama_pengeluaran' => 'required',
            'id_expense_category' => 'required',
            'nominal' => 'required|numeric',
            'qty' => 'required|integer',
            'bukti_nota' => 'nullable|image',
            'nomor_rekening' => 'required|numeric'
        ]);

        $path = null;

        if ($request->hasFile('bukti_nota')) {
            $path = $request->file('bukti_nota')->store('expenses', 'public');
        }

       ExpenseReport::create([
            'id_event' => $eventId,
            'id_user' => auth()->user()->id_user,
            'id_expense_category' => $request->id_expense_category, // 🔥 WAJIB
            'nama_pengeluaran' => $request->nama_pengeluaran,
            'nominal' => $request->nominal,
            'qty' => $request->qty,
            'bukti_nota_path' => $path,
            'nomor_rekening' => $request->nomor_rekening
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        $expense = ExpenseReport::findOrFail($id);

        abort_if($expense->isLockedForModification(), 403, 'Expense yang sudah accepted/declined tidak bisa diubah');

        $request->validate([
            'nama_pengeluaran' => 'required',
            'id_expense_category' => 'required',
            'nominal' => 'required|numeric',
            'qty' => 'required|integer',
            'nomor_rekening' => 'required|numeric',
        ]);

        $expense->update([
            'nama_pengeluaran' => $request->nama_pengeluaran,
            'id_expense_category' => $request->id_expense_category,
            'nominal' => $request->nominal,
            'qty' => $request->qty,
            'nomor_rekening' => $request->nomor_rekening
        ]);

        return back()->with('success', 'Expense updated');
    }

    public function destroy($id)
    {
        $expense = ExpenseReport::findOrFail($id);

        abort_if($expense->isLockedForModification(), 403, 'Expense yang sudah accepted/declined tidak bisa dihapus');

        // optional: delete file juga
        if ($expense->bukti_nota_path) {
            \Storage::disk('public')->delete($expense->bukti_nota_path);
        }

        $expense->delete();

        return back()->with('success', 'Expense deleted');
    }
}
