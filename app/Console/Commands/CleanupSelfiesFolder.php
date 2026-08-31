<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanupSelfiesFolder extends Command
{
    protected $signature = 'presensi:cleanup-selfies 
                        {--months=3 : Hapus file selfie_* lebih tua dari X bulan}
                        {--limit=100 : Maksimal file yang dihapus setiap eksekusi}
                        {--dry : Dry run, tidak menghapus apa pun}';

    protected $description = 'Hapus file selfie_* di Garage yang berusia lebih dari X bulan';

    public function handle()
    {
        $months = (int) $this->option('months');
        $limit = (int) $this->option('limit');
        $dry = (bool) $this->option('dry');

        if ($limit <= 0) {
            $this->error('Limit harus lebih dari 0.');
            return self::FAILURE;
        }

        // Folder di Garage
        $folder = 'presensi/selfies';

        // Disk S3 yang diarahkan ke Garage
        $disk = Storage::disk('s3');

        $threshold = Carbon::now()
            ->subMonths($months);

        $this->info(
            "Mencari selfie_* sebelum: " .
                $threshold->toDateTimeString()
        );

        $this->line("Folder: {$folder}");
        $this->line("Maksimal hapus: {$limit} file");
        $this->line(
            $dry
                ? 'Mode: DRY RUN'
                : 'Mode: ACTUAL (hapus file)'
        );

        try {
            $files = $disk->files($folder);
        } catch (\Throwable $e) {

            $this->error(
                'Gagal membaca folder Garage: ' .
                    $e->getMessage()
            );

            Log::error(
                'CleanupSelfiesFolder: gagal membaca Garage',
                [
                    'folder' => $folder,
                    'error' => $e->getMessage(),
                ]
            );

            return self::FAILURE;
        }

        if (empty($files)) {
            $this->info(
                'Tidak ada file di folder presensi/selfies.'
            );

            return self::SUCCESS;
        }

        $deleted = 0;
        $found = 0;

        foreach ($files as $path) {

            // Stop kalau limit tercapai
            if ($deleted >= $limit) {
                break;
            }

            $filename = basename($path);

            // HANYA file selfie_*
            if (!str_starts_with($filename, 'selfie_')) {
                continue;
            }

            try {

                // Ambil waktu terakhir file dari Garage
                $lastModified = $disk->lastModified($path);

                // Belum lebih tua dari X bulan
                if ($lastModified >= $threshold->timestamp) {
                    continue;
                }

                $found++;

                $modifiedDate = Carbon::createFromTimestamp(
                    $lastModified
                );

                if ($dry) {

                    $this->line(
                        "[DRY] Akan hapus: {$path} | modified: " .
                            $modifiedDate->toDateTimeString()
                    );

                    $deleted++;

                    continue;
                }

                // Hapus dari Garage
                $disk->delete($path);

                $deleted++;

                Log::info(
                    "CleanupSelfiesFolder: deleted from Garage {$path}"
                );

                $this->line(
                    "Hapus: {$path}"
                );
            } catch (\Throwable $e) {

                Log::warning(
                    "CleanupSelfiesFolder: gagal hapus {$path}: " .
                        $e->getMessage()
                );

                $this->error(
                    "Gagal: {$path}"
                );
            }
        }

        $this->newLine();

        $this->info(
            "Selesai. File terhapus: {$deleted}"
        );

        if ($found === 0) {
            $this->info(
                "Tidak ditemukan selfie_* yang lebih tua dari {$months} bulan."
            );
        }

        if ($deleted >= $limit) {
            $this->info(
                "Limit {$limit} tercapai. Cleanup dilanjutkan pada eksekusi berikutnya."
            );
        }

        return self::SUCCESS;
    }
}
