namespace App\Jobs;

use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\Mail;
use App\Mail\CertificateMail;

class SendEmailBlast implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $eventId;
    protected $designFolderId;

    public function handle(GoogleDriveService $gdrive) {
        $participants = Participant::where('event_id', $this->eventId)->get();

        foreach ($participants as $p) {
            // Cari fail: email@domain.com.pdf
            $namaFail = $p->email . ".pdf";
            $failDrive = $gdrive->cariFailBerdasarkanNama($namaFail, $this->designFolderId);

            if ($failDrive) {
                // Kirim Email dengan lampiran ID Drive (atau stream konten)
                Mail::to($p->email)->send(new CertificateMail($p, $failDrive->id));
                
                // Update status di database
                $p->update(['certificate_sent' => true]);
            }
        }
    }
}