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
        foreach ($entitas as $item)
        {
            $slips = PayrollModel::where('entitas_id', $item->id)->get();
            foreach ($slips as $slip){
                preg_match('/(.*)\/(.*)\/(.*)\/(\d*)\z/', $slip->no_slip, $output_array);
                $new_noSlip = "006/DJB-{$item->nama}/HR/20".$output_array['2']."/".$output_array['3']."/".$output_array['4'];
                $this->info($new_noSlip);
                $slip->no_slip=$new_noSlip;
                $slip->save();
            }
        }
    }
}
