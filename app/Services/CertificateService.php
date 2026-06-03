<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use BaconQrCode\Renderer\Image\GDLibRenderer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Storage;


class CertificateService
{
    /**
     * Generate certificate image dengan overlay nama dan QR code
     */
    public function generateCertificate(
        $templatePath,
        $namaOrang,
        $qrToken,
        $recipientEmail = null,
        $outputPath = null,
        $config = null
    )
    {
        try {
            // Baca template image
            $templateFullPath = storage_path('app/public/' . $templatePath);
            
            if (!file_exists($templateFullPath)) {
                throw new \Exception('Template file tidak ditemukan: ' . $templatePath);
            }

            // Initialize ImageManager dengan driver fallback
            $manager = $this->getImageManager();
            $image = $manager->read($templateFullPath);

            // Generate QR code sebagai PNG
            $qrPath = storage_path('app/public/temp_qr_' . uniqid() . '.png');
            $verificationUrl = url('/verify-certificate/' . $qrToken);
            $this->generateQrCode($verificationUrl,$qrPath);

            // Overlay nama penerima berdasarkan config atau default
            try {
                if ($config && isset($config['textBoxes']) && count($config['textBoxes']) > 0) {
                    // Gunakan konfigurasi dari editor

                    foreach ($config['textBoxes'] as $textBox) {
                        $scaleX = 1;
                        $scaleY = 1;

                        if (
                            isset($config['canvasWidth']) &&
                            isset($config['imageWidth']) &&
                            $config['canvasWidth'] > 0
                        ) {
                            $scaleX = $config['imageWidth'] / $config['canvasWidth'];
                        }

                        if (
                            isset($config['canvasHeight']) &&
                            isset($config['imageHeight']) &&
                            $config['canvasHeight'] > 0
                        ) {
                            $scaleY = $config['imageHeight'] / $config['canvasHeight'];
                        }
                        

                        // =========================
                        // DYNAMIC TEXT CONTENT
                        // =========================

                        $textContent = $textBox['text'] ?? '';

                      switch ($textBox['type'] ?? 'static') {

                        case 'recipient_name':
                            $textContent = $this->shortenName($namaOrang);
                            break;

                        case 'recipient_email':
                            $textContent = $recipientEmail ?? '';
                            break;

                        default:
                            $textContent = $textBox['text'] ?? '';
                            break;
                    }
                    $fontSize = max(
                            12,
                            (int)($textBox['fontSize'] * $scaleX)
                        );

                        // $bbox = imagettfbbox(
                        //     $fontSize,
                        //     0,
                        //     public_path('fonts/GoogleSans-Bold.ttf'),
                        //     $textContent
                        // );

                        // $textWidth = $bbox[2] - $bbox[0];  

                        // $posX = (int)(
                        //     ($textBox['left'] * $scaleX)
                        //     - ($textWidth / 2)
                        // );
                        // $posY = (int)($textBox['top'] * $scaleY);
                        $posX = (int)(
                            ($textBox['centerX'] ?? $textBox['left'])
                            * $scaleX
                        );

                        $posY = (int)(
                            ($textBox['centerY'] ?? $textBox['top'])
                            * $scaleY
                        );

                        
                        $color = $this->hexToRgb(
                            $textBox['fill'] ?? '#000000'
                        );
                        $image->text(
                        $textContent,
                        $posX,
                        $posY,
                        function ($textObject) use (
                            $fontSize,
                            $textBox
                        ) {
                            $textObject->filename(
                                public_path('fonts/GoogleSans-Bold.ttf')
                            );

                            $textObject->size($fontSize);

                            $textObject->color(
                                $textBox['fill'] ?? '#000000'
                            );

                            $textObject->align(
                                $textBox['textAlign'] ?? 'center'
                            );

                            $textObject->valign('middle');
                        }
                        );
                    }
                } else {
                    // Gunakan default positioning
                    $image->text($namaOrang, 
                        (int)($image->width() / 2), 
                        (int)($image->height() / 2.5), 
                        function ($textObject) {
                            $textObject->filename(
                                        public_path('fonts/GoogleSans-Bold.ttf')
                                    );
                            $textObject->size(60);
                            // $textObject->color(0, 0, 0);
                             $textObject->color(
                                 $textBox['fill'] ?? '#000000'
                             );
                            $textObject->align('center');
                            // $textObject->valign('center');
                        }
                    );
                }
            } catch (\Exception $e) {
                // Jika text overlay gagal, lanjut tanpa overlay
                dd($e->getMessage());
                \Log::warning('Text overlay failed: ' . $e->getMessage());
            }

            // Overlay QR code (di sudut kanan bawah)
            if (file_exists($qrPath)) {
                $qrManager = $this->getImageManager();
                $qrImage = $qrManager->read($qrPath);
                $image->place($qrImage, 'bottom-right', 30, 30);
            }

            // Save certificate
            if (!$outputPath) {
                $cleanName = preg_replace(
                    '/[^A-Za-z0-9]/',
                    '_',
                    $namaOrang
                );

                $outputPath =
                    'certificates/CERTIFICATE_GDP_' .
                    $cleanName .
                    '.png';
            }

            $fullOutputPath = storage_path('app/public/' . $outputPath);
            
            // Ensure directory exists
            @mkdir(dirname($fullOutputPath), 0755, true);
            
            $image->save($fullOutputPath);

            // Cleanup temp QR
            if (file_exists($qrPath)) {
                unlink($qrPath);
            }

            return $outputPath;
        } catch (\Exception $e) {
            throw new \Exception('Gagal generate certificate: ' . $e->getMessage());
        }
    }

    /**
     * Generate QR code as PNG file
     */
private function generateQrCode($text, $outputPath)
{
    try {

        @mkdir(dirname($fuoutputPathllOutputPath), 0755, true);

        $builder = new \Endroid\QrCode\Builder\Builder(
            writer: new \Endroid\QrCode\Writer\PngWriter(),
            data: $text,
            size: 200,
            margin: 10
        );

        $result = $builder->build();

        $result->saveToFile($outputPath);

    } catch (\Exception $e) {

        dd($e->getMessage());

        $this->createSimpleQrPlaceholder(
            $text,
            $outputPath
        );
    }
}

    /**
     * Create simple QR code placeholder if proper generation fails
     */
    private function createSimpleQrPlaceholder($text, $outputPath)
    {
        // Create a simple gray box with white center and text
        $width = 200;
        $height = 200;
        
        $image = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $gray = imagecolorallocate($image, 200, 200, 200);
        
        // Fill background
        imagefill($image, 0, 0, $white);
        
        // Draw border
        imagerectangle($image, 0, 0, $width - 1, $height - 1, $black);
        
        // Add text (simplified token display)
        $shortToken = substr($text, 0, 12) . '...';
        imagestring($image, 1, 10, 90, 'QR: ' . $shortToken, $black);
        
        // Save image
        imagepng($image, $outputPath);
        imagedestroy($image);
    }

    /**
     * Get ImageManager with driver fallback (Imagick -> GD)
     */
    private function getImageManager()
    {
        // Try Imagick first
        if (extension_loaded('imagick')) {
            try {
                return new ImageManager(new ImagickDriver());
            } catch (\Exception $e) {
                \Log::warning('Imagick driver failed: ' . $e->getMessage());
            }
        }

        // Fallback to GD
        try {
            return new ImageManager(new GdDriver());
        } catch (\Exception $e) {
            throw new \Exception('Neither Imagick nor GD driver available: ' . $e->getMessage());
        }
    }

    /**
     * Generate multiple certificates from bulk data
     */
    public function generateBulkCertificates(
        $templatePath,
        $recipientsList,
        $config = null
    )
    {
        $results = [
            'success' => [],
            'errors' => [],
        ];

        foreach ($recipientsList as $index => $recipient) {
            try {
                $namaOrang = $recipient['nama_penerima'] ?? '';
                $qrToken = $recipient['qr_token'] ?? '';

                if (empty($namaOrang) || empty($qrToken)) {
                    $results['errors'][] = "Baris " . ($index + 1) . ": Nama atau QR token kosong";
                    continue;
                }

                $outputPath = $this->generateCertificate(
                    $templatePath,
                    $namaOrang,
                    $qrToken,
                    $recipient['email_penerima'] ?? null,
                    null,
                    $config
                );

                $results['success'][] = [
                    'nama_penerima' => $namaOrang,
                    'file_url' => $outputPath,
                ];
            } catch (\Exception $e) {
                $results['errors'][] = "Baris " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        return $results;
    }

    private function shortenName($name)
    {
        $maxLength = 25;

        if (mb_strlen($name) <= $maxLength) {
            return $name;
        }

        $parts = explode(' ', trim($name));

        if (count($parts) < 2) {
            return $name;
        }

        $last = array_pop($parts);

        return implode(' ', $parts)
            . ' '
            . strtoupper(substr($last, 0, 1))
            . '.';
    }

    /**
     * Convert hex color to RGB array
     */
    private function hexToRgb($hex)
    {
        $hex = str_replace('#', '', $hex);
        
        if (strlen($hex) == 3) {
            $hex = str_repeat($hex[0], 2) . str_repeat($hex[1], 2) . str_repeat($hex[2], 2);
        }
        
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }
}
