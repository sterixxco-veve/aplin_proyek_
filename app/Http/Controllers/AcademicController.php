<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class AcademicController extends Controller
{
    /**
     * Pastikan User ID 1 selalu ada
     */
    private function ensureUserExists()
    {
        $userId = Auth::id() ?? 1;
        User::firstOrCreate(['id' => 1], [
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => bcrypt('password')
        ]);
        return $userId;
    }

    public function getDashboardData()
    {
        $userId = $this->ensureUserExists();
        $courses = Course::with(['schedules', 'grade'])->where('user_id', $userId)->get();
        $gpa = 4.0; // Contoh statis
        $totalCredits = $courses->sum('credits');

        return view('dashboard', compact('courses', 'gpa', 'totalCredits'));
    }

    public function storeFromMagicBox(Request $request)
    {
        $userId = $this->ensureUserExists();
        $pattern = "/([A-Z0-9-]{2,})\s+(.+?)\s+(\d+)\s+([A-E][+-]?|\d+\.?\d*)\s+(Senin|Selasa|Rabu|Kamis|Jumat|Sabtu|Minggu)\s+(\d{1,2}:\d{2})\s?-\s?(\d{1,2}:\d{2})/i";
        preg_match_all($pattern, $request->raw_text, $matches, PREG_SET_ORDER);

        if (empty($matches)) return response()->json(['message' => 'Format salah'], 422);

        DB::transaction(function() use ($matches, $userId, $request) {
            foreach ($matches as $match) {
                $course = Course::updateOrCreate(
                    ['user_id' => $userId, 'code' => $match[1], 'semester_taken' => $request->semester],
                    ['name' => $match[2], 'credits' => $match[3]]
                );
                Schedule::updateOrCreate(
                    ['course_id' => $course->id, 'day' => $match[5]],
                    ['start_time' => $match[6], 'end_time' => $match[7]]
                );
            }
        });

        return response()->json(['message' => 'Berhasil']);
    }

    public function exportPdf(Request $request)
    {
        $userId = $this->ensureUserExists();
        $courses = Course::with('schedules')->where('user_id', $userId)->get();

        if ($courses->isEmpty()) return "Data kosong!";

        $data = ['title' => 'Jadwal EduPath', 'date' => date('d/m/Y'), 'courses' => $courses];

        // Jika user kesulitan dengan IDM, tambahkan ?html=1 di URL
        if ($request->has('html')) {
            return view('pdf.schedule', $data);
        }

        // Generate PDF asli (Akan tetap ditangkap IDM jika IDM terinstal)
        $pdf = Pdf::loadView('pdf.schedule', $data)->setPaper('a4', 'portrait');
        return $pdf->stream('Jadwal.pdf');
    }
}