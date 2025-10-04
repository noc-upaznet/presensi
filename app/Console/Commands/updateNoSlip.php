<?php

namespace App\Console\Commands;

use App\Models\M_Entitas;
use App\Models\PayrollModel;
use Illuminate\Console\Command;

class updateNoSlip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-no-slip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Mapping bulan angka ke romawi
        $bulanRomawi = [
            '01' => 'I',  '02' => 'II', '03' => 'III',
            '04' => 'IV', '05' => 'V',  '06' => 'VI',
            '07' => 'VII','08' => 'VIII','09' => 'IX',
            '10' => 'X',  '11' => 'XI', '12' => 'XII',
        ];

        $entitas = M_Entitas::all();

        foreach ($entitas as $item) {
            $slips = PayrollModel::where('entitas_id', $item->id)->get();

            foreach ($slips as $slip) {
                // Pecah no_slip berdasarkan "/"
                $parts = explode('/', $slip->no_slip);

                // Struktur: [0]=006, [1]=DJB-xxx, [2]=HR, [3]=2025, [4]=IX, [5]=001
                $tahun = $parts[3];
                $bulan = $parts[4]; // ini yang mau dicek
                $nomor = $parts[5];

                // Jika bulan sekarang IX (September) → ganti jadi X (Oktober)
                if ($bulan === 'IX') {
                    $new_noSlip = "{$parts[0]}/{$parts[1]}/{$parts[2]}/{$tahun}/X/{$nomor}";

                    $slip->no_slip = $new_noSlip;
                    $slip->save();

                    $this->info("Updated: {$slip->id} → {$new_noSlip}");
                }
            }
        }
    }
}
