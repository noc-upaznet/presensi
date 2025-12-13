<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanupSelfiesFolder extends Command
{
    protected $signature = 'presensi:cleanup-selfies 
                            {--months=3 : Hapus file selfie_* lebih tua dari X bulan}
                            {--dry : Dry run, tidak menghapus apa pun}';

    protected $description = 'Hapus file selfie_* di storage/app/public/selfies yang berusia lebih dari X bulan';

    public function handle()
    {
        $months = (int) $this->option('months');
        $dry = (bool) $this->option('dry');

        $selfiesPath = storage_path('app/public/selfies');

        if (!File::exists($selfiesPath)) {
            $this->error("Folder tidak ditemukan: {$selfiesPath}");
            return 1;
        }

        $threshold = Carbon::now()->subMonths($months)->timestamp;

        $this->info("Menghapus file selfie_* sebelum: " . Carbon::createFromTimestamp($threshold)->toDateTimeString());
        $this->line($dry ? 'Mode: DRY RUN' : 'Mode: ACTUAL (hapus file)');

        $files = File::files($selfiesPath);

        if (empty($files)) {
            $this->info('Tidak ada file di folder selfies.');
            return 0;
        }

        $deleted = 0;

        foreach ($files as $file) {
            $filename = $file->getFilename();

            // HANYA file selfie_*
            if (!str_starts_with($filename, 'selfie_')) {
                continue;
            }

            $lastModified = $file->getMTime(); // unix timestamp

            // Skip jika belum lebih dari X bulan
            if ($lastModified >= $threshold) {
                continue;
            }

            if ($dry) {
                $this->line("[DRY] Akan hapus: {$filename} (modified: " .
                    Carbon::createFromTimestamp($lastModified)->toDateTimeString() . ")");
                continue;
            }

            try {
                File::delete($file->getPathname());
                $deleted++;
                Log::info("CleanupSelfiesFolder: deleted {$filename}");
            } catch (\Throwable $e) {
                Log::warning("CleanupSelfiesFolder: gagal hapus {$filename}: " . $e->getMessage());
            }
        }

        $this->info("Selesai. File terhapus: {$deleted}");

        return 0;
    }
}
