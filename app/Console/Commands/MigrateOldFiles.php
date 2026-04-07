<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateOldFiles extends Command
{
    protected $signature = 'app:migrate-old-files';
    protected $description = 'Migrate file lama ke Garage S3';

    public function handle()
    {
        $basePath = storage_path('app/public/file-pengajuan-dispensasi');
        $files = glob($basePath . '/*');

        $this->info("Total file ditemukan: " . count($files));

        foreach ($files as $fullPath) {

            $filename = basename($fullPath);
            $this->info("File: " . $filename);

            if (!file_exists($fullPath)) {
                $this->error("File tidak ditemukan: " . $filename);
                continue;
            }

            $fileDate = filemtime($fullPath);
            $limitDate = strtotime('2026-04-07 23:59:59');

            if ($fileDate > $limitDate) {
                $this->warn("Skip (tanggal baru): $filename");
                continue;
            }

            $sizeMB = filesize($fullPath) / 1024 / 1024;

            // jika >1.5MB → compress / convert
            if ($sizeMB >= 1.5) {
                $this->warn("Processing: $filename (" . round($sizeMB, 2) . " MB)");
                $fullPath = $this->compressToMaxSize($fullPath, 2);
                $filename = basename($fullPath);
                $sizeMB = filesize($fullPath) / 1024 / 1024; // update setelah compress
                $this->info("After process: " . round($sizeMB, 2) . " MB");
            }

            $this->info("SIZE: " . round($sizeMB, 2) . " MB");

            $targetPath = 'file-pengajuan-dispensasi/' . $filename;

            $oldPngPath = 'file-pengajuan-dispensasi/' . pathinfo($filename, PATHINFO_FILENAME) . '.png';

            if ($oldPngPath !== $targetPath) {
                Storage::disk('s3')->delete($oldPngPath);
            }

            try {
                Storage::disk('s3')->put($targetPath, fopen($fullPath, 'r'));

                if ($oldPngPath !== $targetPath) {
                    Storage::disk('s3')->delete($oldPngPath);
                }

                $this->info("Uploaded: " . $targetPath);
            } catch (\Exception $e) {
                $this->error("Gagal upload: $filename | " . $e->getMessage());
            }
        }

        $this->info("✅ MIGRASI + COMPRESS SELESAI");
    }

    private function compressToMaxSize($source, $maxSizeMB = 2)
    {
        $maxBytes = $maxSizeMB * 1024 * 1024;

        $info = getimagesize($source);
        if (!$info) return $source;

        $mime = $info['mime'];

        // PNG → convert ke JPG (lebih efektif)
        if ($mime == 'image/png') {

            $this->warn("Convert PNG to JPG: " . basename($source));

            $image = imagecreatefrompng($source);

            // WAJIB: resize dulu
            $width = imagesx($image);
            if ($width > 1280) {
                $image = imagescale($image, 1280);
            }

            $newPath = preg_replace('/\.png$/i', '.jpg', $source);

            imagejpeg($image, $newPath, 80);

            imagedestroy($image);

            unlink($source); // hapus PNG lama
            $this->info("Converted to JPG: " . basename($newPath));
            return $newPath;
        }

        // JPEG compress bertahap
        if ($mime == 'image/jpeg') {

            $image = imagecreatefromjpeg($source);

            $quality = 70;

            do {
                imagejpeg($image, $source, $quality);

                clearstatcache();
                $currentSize = filesize($source);

                $quality -= 10;
            } while ($currentSize > $maxBytes && $quality > 10);

            imagedestroy($image);
        }

        return $source;
    }
}
