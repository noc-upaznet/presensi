<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReplacePresensiFiles extends Command
{
    protected $signature = 'presensi:replace-files 
                            {--months=3 : Ganti file yang lebih tua dari {months} bulan}
                            {--dry : Dry run, tampilkan jumlah tapi tidak melakukan update}
                            {--delete-files : Hapus file fisik di disk jika ditemukan}';

    protected $description = 'Ganti kolom file pada tabel presensi menjadi file dummy untuk data lebih tua dari X bulan';

    public function handle()
    {
        $months = (int) $this->option('months');
        $dry = (bool) $this->option('dry');
        $delFiles = (bool) $this->option('delete-files');

        // Path yang akan disimpan di DB (sesuaikan jika frontend mengharapkan path berbeda)
        // Disarankan file dummy berada di storage/app/public/assets/img/... atau public/assets/img/...
        $dummyPath = 'dummy-pic.png';

        $threshold = Carbon::now()->subMonths($months)->startOfDay();
        $this->info("Threshold: data dengan `tanggal` sebelum {$threshold->toDateString()} akan diproses.");
        $this->line($dry ? 'Mode: DRY RUN (tidak mengubah DB).' : 'Mode: ACTUAL (akan mengubah DB).');
        if ($delFiles) {
            $this->line('Opsi: akan mencoba menghapus file fisik di folder storage/app/public/selfies jika ditemukan.');
        }

        // Hitung total target (untuk info) — hanya untuk rows yang belum berisi dummy
        $totalTarget = DB::table('presensi')
            ->where('tanggal', '<', $threshold)
            ->where(function ($q) use ($dummyPath) {
                // proses baris yang file NULL, empty string, atau bukan dummyPath
                $q->whereNull('file')
                    ->orWhere('file', '')
                    ->orWhere('file', '!=', $dummyPath);
            })
            ->count();

        $this->info("Total baris yang cocok (perkiraan): {$totalTarget}");

        if ($totalTarget === 0) {
            $this->info('Tidak ada data untuk diproses.');
            return 0;
        }

        if ($dry) {
            $this->info('Dry-run selesai. Tidak ada perubahan dibuat.');
            return 0;
        }

        $batchSize = 1000;
        $processed = 0;

        while (true) {
            // ambil batch *fresh* tiap iterasi, hanya rows yang belum dummy
            $rows = DB::table('presensi')
                ->select('id', 'file')
                ->where('tanggal', '<', $threshold)
                ->where(function ($q) use ($dummyPath) {
                    $q->whereNull('file')
                        ->orWhere('file', '')
                        ->orWhere('file', '!=', $dummyPath);
                })
                ->orderBy('id')
                ->limit($batchSize)
                ->get();

            if ($rows->isEmpty()) {
                break;
            }

            $ids = $rows->pluck('id')->toArray();

            // opsional: hapus file fisik lama jika diminta (HANYA untuk folder selfies di storage/app/public/selfies)
            if ($delFiles) {
                foreach ($rows as $r) {
                    $currentFile = $r->file;

                    // Skip jika kosong atau sudah dummy
                    if (empty($currentFile) || $currentFile === $dummyPath) {
                        continue;
                    }

                    // Pastikan hanya file selfie
                    // Contoh valid:
                    // selfie_123.jpg
                    // selfies/selfie_123.jpg
                    if (
                        !str_contains($currentFile, 'selfie_') ||
                        !str_contains($currentFile, 'selfies')
                    ) {
                        continue;
                    }

                    // Pastikan path relatif ke folder selfies
                    // Jika DB menyimpan: selfie_xxx.jpg → jadikan selfies/selfie_xxx.jpg
                    if (!str_contains($currentFile, '/')) {
                        $relativePath = 'selfies/' . $currentFile;
                    } else {
                        $relativePath = ltrim($currentFile, '/');
                    }

                    // Lokasi fisik file
                    $realPath = storage_path('app/public/' . $relativePath);

                    if (File::exists($realPath) && File::isFile($realPath)) {
                        try {
                            File::delete($realPath);
                            Log::info("ReplacePresensiFiles: deleted selfie file -> {$realPath} (presensi_id: {$r->id})");
                        } catch (\Throwable $e) {
                            Log::warning("ReplacePresensiFiles: gagal menghapus {$realPath}: " . $e->getMessage());
                        }
                    } else {
                        Log::info("ReplacePresensiFiles: selfie file not found -> {$realPath} (presensi_id: {$r->id})");
                    }
                }
            }

            // lakukan update hanya untuk ids yang diambil
            try {
                DB::beginTransaction();
                $updated = DB::table('presensi')
                    ->whereIn('id', $ids)
                    ->update(['file' => $dummyPath]);
                DB::commit();

                $processed += $updated;
                $this->info("Batch diproses (ids diambil: " . count($ids) . "): baris terupdate = {$updated}. Total terproses: {$processed}");
                Log::info("ReplacePresensiFiles: batch update {$updated} baris.");
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error("ReplacePresensiFiles error: " . $e->getMessage());
                $this->error("Terjadi error saat update: " . $e->getMessage());
                return 1;
            }

            // Jika jumlah ids lebih kecil dari batchSize, kemungkinan telah habis
            if (count($ids) < $batchSize) {
                break;
            }
        }

        $this->info("Selesai. Total baris diupdate: {$processed}");
        Log::info("ReplacePresensiFiles selesai. Total diupdate: {$processed}");

        return 0;
    }
}
