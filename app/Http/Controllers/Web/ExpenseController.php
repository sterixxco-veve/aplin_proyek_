<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use App\Models\Event;
use App\Models\ExpenseReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function home()
    {
        $events = Event::visibleTo(auth()->user())
            ->latest()
            ->get()
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
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        $financial_summary = $event->financial_summary;
        $expenses = ExpenseReport::with(['category', 'user'])
            ->where('id_event', $eventId)
            ->latest('id_expense')
            ->get();
        $categories = ExpenseCategory::whereNotIn('nama_kategori', ['Pemasukan', 'Pemasukkan', 'pemasukan', 'pemasukkan'])->get();
        $expenseCategories = $categories;


        return view('events.finance', compact(
            'event',
            'expenses',
            'eventId',
            'financial_summary',
            'categories', // 🔥 WAJIB
            'expenseCategories'
        ));
    }

    public function export($eventId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        $filename = 'finance-' . $event->id_event . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($event) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'nama_pengeluaran',
                'category_name',
                'nominal',
                'qty',
                'nomor_rekening',
                'approval_status',
                'rejection_reason',
                'is_reimbursed',
                'bukti_nota_path',
                'created_by',
                'created_at',
            ]);

            ExpenseReport::with(['category', 'user'])
                ->where('id_event', $event->id_event)
                ->orderBy('id_expense')
                ->chunk(200, function ($expenses) use ($handle) {
                    foreach ($expenses as $expense) {
                        fputcsv($handle, [
                            $expense->nama_pengeluaran,
                            $expense->category?->nama_kategori,
                            $expense->nominal,
                            $expense->qty,
                            $expense->nomor_rekening,
                            $expense->approval_status,
                            $expense->rejection_reason,
                            $expense->is_reimbursed ? 1 : 0,
                            $expense->bukti_nota_path,
                            $expense->user?->email,
                            optional($expense->created_at)->format('Y-m-d H:i:s'),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function import(Request $request, $eventId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);

        $request->validate([
            'finance_csv' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('finance_csv')->getRealPath();
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return back()->with('error', 'CSV tidak bisa dibaca.');
        }

        $headers = fgetcsv($handle);
        if ($headers === false) {
            fclose($handle);
            return back()->with('error', 'CSV kosong.');
        }

        $headers = array_map(function ($header) {
            $header = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header);
            $header = strtolower(trim($header));

            return str_replace([' ', '-'], '_', $header);
        }, $headers);

        $errors = [];
        $rows = [];
        $line = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $line++;

            if (!array_filter($data, fn ($value) => trim((string) $value) !== '')) {
                continue;
            }

            $row = array_combine($headers, array_pad($data, count($headers), null));
            $row = array_map(fn ($value) => is_string($value) ? trim($value) : $value, $row);

            $categoryId = null;
            if (!empty($row['id_expense_category'])) {
                $categoryId = $row['id_expense_category'];
                if (!ExpenseCategory::where('id_expense_category', $categoryId)->exists()) {
                    $errors[] = "Baris {$line}: kategori expense tidak ditemukan untuk id_expense_category {$categoryId}.";
                    continue;
                }
            } else {
                $categoryName = $row['category_name'] ?? $row['nama_kategori'] ?? null;
                if (!$categoryName) {
                    $errors[] = "Baris {$line}: category_name wajib diisi.";
                    continue;
                }

                $category = ExpenseCategory::whereRaw('LOWER(nama_kategori) = ?', [strtolower($categoryName)])->first();
                if (!$category) {
                    $errors[] = "Baris {$line}: kategori \"{$categoryName}\" tidak ditemukan.";
                    continue;
                }

                $categoryId = $category->id_expense_category;
            }

            $namaPengeluaran = $row['nama_pengeluaran'] ?? null;
            $nominal = $row['nominal'] ?? null;
            $qty = $row['qty'] ?? null;
            $nomorRekening = $row['nomor_rekening'] ?? null;

            if (!$namaPengeluaran || !is_numeric($nominal) || !is_numeric($qty) || !$nomorRekening) {
                $errors[] = "Baris {$line}: nama_pengeluaran, nominal, qty, dan nomor_rekening wajib diisi.";
                continue;
            }

            $approvalStatus = strtolower($row['approval_status'] ?? 'pending');
            if ($approvalStatus === 'declined') {
                $approvalStatus = 'rejected';
            }

            if (!in_array($approvalStatus, ['pending', 'accepted', 'rejected'], true)) {
                $errors[] = "Baris {$line}: approval_status harus pending, accepted, declined, atau rejected.";
                continue;
            }

            $isReimbursed = in_array(strtolower((string) ($row['is_reimbursed'] ?? '0')), ['1', 'true', 'yes', 'ya'], true);

            // Normalisasi status UI/CSV ke status database.
            // UI boleh memakai "declined", tetapi database tetap memakai "rejected".
            // Reimbursed secara logika harus sudah accepted.
            if ($isReimbursed) {
                $approvalStatus = 'accepted';
            }

            if ($approvalStatus === 'rejected') {
                $isReimbursed = false;
            }

            $rejectionReason = $approvalStatus === 'rejected'
                ? ($row['rejection_reason'] ?? null)
                : null;

            $isAccepted = $approvalStatus === 'accepted';

            $rows[] = [
                'id_event' => $event->id_event,
                'id_user' => auth()->user()->id_user,
                'id_expense_category' => $categoryId,
                'nama_pengeluaran' => $namaPengeluaran,
                'nominal' => $nominal,
                'qty' => $qty,
                'nomor_rekening' => $nomorRekening,
                'bukti_nota_path' => $row['bukti_nota_path'] ?? null,
                'approval_status' => $approvalStatus,
                'rejection_reason' => $rejectionReason,
                'approved_by' => $isAccepted ? auth()->user()->id_user : null,
                'approved_at' => $isAccepted ? now() : null,
                'is_reimbursed' => $isReimbursed,
                'reimbursed_at' => $isReimbursed ? now() : null,
            ];
        }

        fclose($handle);

        if (!empty($errors)) {
            return back()->with('error', implode(' ', $errors));
        }

        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                ExpenseReport::create($row);
            }
        });

        return back()->with('success', 'Berhasil import ' . count($rows) . ' data finance.');
    }

    public function store(Request $request, $eventId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);

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
            'id_event' => $event->id_event,
            'id_user' => auth()->user()->id_user,
            'id_expense_category' => $request->id_expense_category, // 🔥 WAJIB
            'nama_pengeluaran' => $request->nama_pengeluaran,
            'nominal' => $request->nominal,
            'qty' => $request->qty,
            'bukti_nota_path' => $path,
            'nomor_rekening' => $request->nomor_rekening,
            'approval_status' => 'pending',
            'rejection_reason' => null,
            'approved_by' => null,
            'approved_at' => null,
            'is_reimbursed' => false,
            'reimbursed_at' => null,
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        $expense = ExpenseReport::findOrFail($id);
        $event = Event::visibleTo(auth()->user())->findOrFail($expense->id_event);

        abort_unless($event->canManageOperationalBy(auth()->user()), 403, 'Anda tidak punya akses untuk mengubah expense ini.');

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
        $event = Event::visibleTo(auth()->user())->findOrFail($expense->id_event);

        abort_unless($event->canManageOperationalBy(auth()->user()), 403, 'Anda tidak punya akses untuk menghapus expense ini.');

        // optional: delete file juga
        if ($expense->bukti_nota_path) {
            \Storage::disk('public')->delete($expense->bukti_nota_path);
        }

        $expense->delete();

        return back()->with('success', 'Expense deleted');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            // "declined" hanya dipakai oleh UI. Nilai yang disimpan ke database tetap "rejected".
            'status' => 'required|in:pending,accepted,declined,reimbursed',
            'rejection_reason' => 'required_if:status,declined|nullable|string|max:1000',
        ]);

        $expense = ExpenseReport::findOrFail($id);
        $event = Event::visibleTo(auth()->user())->findOrFail($expense->id_event);

        abort_unless($event->canManageOperationalBy(auth()->user()), 403, 'Anda tidak punya akses untuk mengubah status expense ini.');

        $status = strtolower((string) $request->input('status'));

        if ($status === 'declined') {
            $expense->approval_status = 'rejected';
            $expense->is_reimbursed = false;
            $expense->rejection_reason = trim((string) $request->input('rejection_reason', ''));
            $expense->approved_by = null;
            $expense->approved_at = null;
            $expense->reimbursed_at = null;
        } elseif ($status === 'accepted') {
            $expense->approval_status = 'accepted';
            $expense->is_reimbursed = false;
            $expense->rejection_reason = null;
            $expense->approved_by = auth()->user()->id_user;
            $expense->approved_at = $expense->approved_at ?? now();
            $expense->reimbursed_at = null;
        } elseif ($status === 'reimbursed') {
            $expense->approval_status = 'accepted';
            $expense->is_reimbursed = true;
            $expense->rejection_reason = null;
            $expense->approved_by = $expense->approved_by ?: auth()->user()->id_user;
            $expense->approved_at = $expense->approved_at ?? now();
            $expense->reimbursed_at = $expense->reimbursed_at ?? now();
        } else {
            // pending
            $expense->approval_status = 'pending';
            $expense->is_reimbursed = false;
            $expense->rejection_reason = null;
            $expense->approved_by = null;
            $expense->approved_at = null;
            $expense->reimbursed_at = null;
        }

        $expense->save();

        $displayStatus = $expense->approval_status === 'rejected'
            ? 'declined'
            : ($expense->is_reimbursed ? 'reimbursed' : $expense->approval_status);

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'approval_status' => $expense->approval_status,
            'display_status' => $displayStatus,
            'is_reimbursed' => (bool) $expense->is_reimbursed,
        ]);
    }
}
