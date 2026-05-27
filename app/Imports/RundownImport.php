<?php

namespace App\Imports;

use App\Models\EventRundownItem;
use App\Models\EventCommittee;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RundownImport implements ToArray, WithHeadingRow
{
    private $eventId;
    private $errors = [];
    private $imported = 0;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * Convert time value (could be object or string) to HH:mm format
     */
    private function parseTimeValue($value)
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        // If it's a string, return as-is
        if (is_string($value)) {
            $trimmed = trim($value);
            if (preg_match('/^\d{1,2}:\d{2}/', $trimmed)) {
                return $trimmed;
            }
            return null;
        }

        // If it's numeric (Excel time serial), convert to HH:mm
        if (is_numeric($value)) {
            // Excel time is stored as decimal (0-1 for 24 hours)
            $seconds = $value * 86400;
            $hours = intdiv($seconds, 3600);
            $minutes = intdiv($seconds % 3600, 60);
            return sprintf('%02d:%02d', $hours, $minutes);
        }

        // If it's an object (like DateTime), format it
        if (is_object($value) && method_exists($value, 'format')) {
            return $value->format('H:i');
        }

        return null;
    }

    public function array(array $rows)
    {
        if (empty($rows)) {
            throw new \Exception('File kosong');
        }

        foreach ($rows as $index => $row) {
            try {
                // Skip completely empty rows
                if (empty(array_filter($row, function($val) { return $val !== null && $val !== ''; }))) {
                    continue;
                }

                // Build normalized row data
                $rowData = [];
                foreach ($row as $key => $value) {
                    $cleanKey = strtolower(trim($key ?? ''));
                    $rowData[$cleanKey] = $value;
                }

                // Extract fields
                $dayNumber = (int)(
                    $rowData['hari'] ?? 
                    $rowData['day'] ?? 
                    $row['Hari'] ?? 
                    $row['Day'] ?? 
                    null
                );
                
                $sessionGroup = trim(
                    ($rowData['sesi'] ?? $rowData['session'] ?? $row['Sesi'] ?? $row['Session'] ?? '') . ''
                );
                
                // Parse time fields properly
                $waktuMulai = $this->parseTimeValue(
                    $rowData['waktu_mulai'] ??
                    $rowData['start_time'] ??
                    null
                );

                $waktuSelesai = $this->parseTimeValue(
                    $rowData['waktu_selesai'] ??
                    $rowData['end_time'] ??
                    null
                );
                
                $kegiatan = trim(
                    ($rowData['kegiatan'] ?? $rowData['activity'] ?? $row['Kegiatan'] ?? $row['Activity'] ?? '') . ''
                );
                
                $picName = trim(
                    ($rowData['penanggung jawab'] ?? $rowData['pic'] ?? $row['Penanggung Jawab'] ?? $row['PIC'] ?? '') . ''
                );

                // Validation
                if (!$dayNumber || !$waktuMulai || !$waktuSelesai || !$kegiatan) {
                    $this->errors[] = "Baris " . ($index + 2) . ": Field wajib tidak lengkap. Hari={$dayNumber}, WaktuMulai={$waktuMulai}, WaktuSelesai={$waktuSelesai}, Kegiatan={$kegiatan}";
                    continue;
                }

                // Find committee by user name (optional)
                $assignedTo = null;
                if (!empty($picName)) {
                    $committee = EventCommittee::whereHas('user', function($q) use ($picName) {
                        $q->where('name', 'like', '%' . trim($picName) . '%');
                    })->where('id_event', $this->eventId)->first();
                    
                    if (!$committee) {
                        $this->errors[] = "Baris " . ($index + 2) . ": PIC '{$picName}' tidak ditemukan di committee";
                        continue;
                    }
                    $assignedTo = $committee->id_comm;
                }

                // Create rundown item
                EventRundownItem::create([
                    'id_event' => $this->eventId,
                    'day_number' => $dayNumber,
                    'session_group' => $sessionGroup ?: null,
                    'waktu_mulai' => $waktuMulai,
                    'waktu_selesai' => $waktuSelesai,
                    'kegiatan' => $kegiatan,
                    'assigned_to' => $assignedTo,
                ]);

                $this->imported++;
            } catch (\Exception $e) {
                $this->errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
            }
        }
    }

    public function getImported()
    {
        return $this->imported;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
