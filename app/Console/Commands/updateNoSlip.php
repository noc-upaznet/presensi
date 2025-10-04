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
        $entitas = M_Entitas::all();

        foreach ($entitas as $item) {
            $slips = PayrollModel::where('entitas_id', $item->id)->get();

            foreach ($slips as $slip) {
                // Pecah no_slip berdasarkan "/"
                $parts = explode('/', $slip->no_slip);

                // Struktur: [0]=006, [1]=DJB-MC, [2]=HR, [3]=2025, [4]=X, [5]=003
                $prefix = $parts[0];
                $entitasPart = $parts[1];
                $hr = $parts[2];
                $tahun = $parts[3];
                $bulan = $parts[4];
                $nomor = $parts[5];

                // Hanya ubah kalau entitasPart = DJB-MC
                if ($entitasPart === 'DJB-MC') {
                    $entitasPart = 'MC'; // ganti langsung ke MC

                    $new_noSlip = "{$prefix}/{$entitasPart}/{$hr}/{$tahun}/{$bulan}/{$nomor}";

                    $slip->no_slip = $new_noSlip;
                    $slip->save();

                    $this->info("Updated: {$slip->id} → {$new_noSlip}");
                }
            }
        }
    }
}
