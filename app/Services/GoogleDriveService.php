namespace App\Services;

use Google\Client;
use Google\Service\Drive;

class GoogleDriveService {
    protected $service;

    public function __construct() {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Drive::DRIVE);
        $this->service = new Drive($client);
    }

    public function binaStrukturFolderAcara($namaAcara) {
        // Bina Folder Utama
        $folderUtama = $this->buatFolder($namaAcara);
        
        $subfolders = ['01_Admin', '02_Design', '03_Finance', '04_Partner'];
        $hasilSub = [];

        foreach ($subfolders as $folder) {
            $f = $this->buatFolder($folder, $folderUtama->id);
            $hasilSub[$folder] = $f->id;
        }

        return [
            'root_id' => $folderUtama->id,
            'subs' => $hasilSub
        ];
    }

    private function buatFolder($nama, $parentId = null) {
        $metadata = new Drive\DriveFile([
            'name' => $nama,
            'mimeType' => 'application/vnd.google-apps.folder'
        ]);
        if ($parentId) $metadata->setParents([$parentId]);
        return $this->service->files->create($metadata, ['fields' => 'id']);
    }
    
    public function muatNaikFail(UploadedFile $fail, $folderId) {
        $fileMetadata = new Drive\DriveFile([
            'name' => $fail->getClientOriginalName(),
            'parents' => [$folderId]
        ]);

        $content = file_get_contents($fail->getRealPath());

        $failDrive = $this->service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $fail->getClientMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        return $failDrive->id;
    }
    public function cariFailBerdasarkanNama($namaFail, $folderId) {
        $optParams = [
            'q' => "name = '$namaFail' and '$folderId' in parents and trashed = false",
            'fields' => 'files(id, name, webViewLink)'
        ];
        
        $results = $this->service->files->listFiles($optParams);
        
        return count($results->getFiles()) > 0 ? $results->getFiles()[0] : null;
    }
}
