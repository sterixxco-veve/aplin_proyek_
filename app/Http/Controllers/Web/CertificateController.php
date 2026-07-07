<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Certificate;
use Illuminate\Http\Request; // Tambahkan ini di paling atas jika belum ada

class CertificateController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $events = Event::visibleTo($user)
            ->withCount('certificates')
            ->latest()
            ->get();

        return view(
            'certificates.index',
            compact('events')
        );
    }

    public function showEvent($eventId)
    {
        $event = Event::visibleTo(auth()->user())
            ->with('certificates')
            ->findOrFail($eventId);

        return view('certificates.show', [
            'event' => $event,
            'certificates' => $event->certificates,
            'canManageCertificate' => $event->canManageCertificateBy(auth()->user()),
            'templatePath' => session('template_path')
        ]);
    }

    // TAMBAHKAN METHOD BARU INI UNTUK MENANGKAP INPUT NRP
    public function bulkInsert(Request $request, $eventId)
    {
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);

        // Validasi hak akses user terhadap event tersebut
        if (!$event->canManageCertificateBy(auth()->user())) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengelola sertifikat.');
        }

        // Validasi input array
        $request->validate([
            'nrp' => 'nullable|array',
            'nrp.*' => 'nullable|string|max:50',
            'nama_penerima' => 'required|array',
            'nama_penerima.*' => 'required|string|max:255',
            'email_penerima' => 'required|array',
            'email_penerima.*' => 'required|email|max:255',
        ]);

        $nrps = $request->input('nrp');
        $names = $request->input('nama_penerima');
        $emails = $request->input('email_penerima');

        $insertedCount = 0;

        foreach ($names as $index => $name) {
            if (!empty($name) && !empty($emails[$index])) {
                Certificate::create([
                    'id_event' => $event->id_event, // atau sesuaikan dengan primary key event Anda
                    'nrp' => $nrps[$index] ?? null,  // Menyimpan NRP (bisa berharga null)
                    'nama_penerima' => $name,
                    'email_penerima' => $emails[$index],
                ]);
                $insertedCount++;
            }
        }

        return redirect()->back()->with('success', "Berhasil menambahkan {$insertedCount} penerima certificate.");
    }

    public function verify($token)
    {
        $certificate = Certificate::with('event')
            ->where('qr_token', $token)
            ->first();

        if (!$certificate) {
            abort(404);
        }

        return view(
            'certificates.verify',
            compact('certificate')
        );
    }
}