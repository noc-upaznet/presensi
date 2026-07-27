<?php

namespace App\Console\Commands;

use App\Models\M_JadwalShift;
use Illuminate\Console\Command;

class GenerateShiftCode extends Command
{
    protected $signature = 'shift:generate-code';

    protected $description = 'Generate kode shift otomatis';

    public function handle()
    {
        $used = [];

        foreach (M_JadwalShift::orderBy('id')->get() as $shift) {

            $nama = trim($shift->nama_shift);

            $lower = strtolower($nama);

            // normalisasi
            $normal = str_replace(':', '.', $nama);
            $normal = preg_replace('/\s+/', '', $normal);

            switch (true) {

                /*
                |--------------------------------------------------------------------------
                | SHIFT KHUSUS
                |--------------------------------------------------------------------------
                */

                case str_contains($lower, 'cuti'):
                    $kode = 'CUT';
                    break;

                case str_contains($lower, 'libur'):
                    $kode = 'LIB';
                    break;

                case str_contains($lower, 'izin setengah'):
                    $kode = 'IZN12';
                    break;

                case str_contains($lower, 'izin'):
                    $kode = 'IZN';
                    break;

                case str_contains($lower, 'sales pagi full'):
                    $kode = 'SPF';
                    break;

                case str_contains($lower, 'sales pagi'):
                    $kode = 'SPG';
                    break;

                case str_contains($lower, 'sales'):
                    $kode = 'SLS';
                    break;

                case str_contains($lower, 'konter minggu pagi'):
                    $kode = 'KMP';
                    break;

                case str_contains($lower, 'konter minggu sore'):
                    $kode = 'KMS';
                    break;

                case str_contains($lower, 'konter pagi'):
                    $kode = 'KPG';
                    break;

                case str_contains($lower, 'konter sore'):
                    $kode = 'KSR';
                    break;

                case str_contains($lower, 'helpdesk lebaran h1 sore'):
                    $kode = 'HL1S';
                    break;

                case str_contains($lower, 'helpdesk lebaran h1'):
                    $kode = 'HL1';
                    break;

                case str_contains($lower, 'helpdesk lebaran h2 sore'):
                    $kode = 'HL2S';
                    break;

                case str_contains($lower, 'helpdesk lebaran h2'):
                    $kode = 'HL2';
                    break;

                case str_contains($lower, 'helpdesk lebaran h3 sore'):
                    $kode = 'HL3S';
                    break;

                case str_contains($lower, 'helpdesk lebaran h3'):
                    $kode = 'HL3';
                    break;

                case str_contains($lower, 'helpdesk lebaran h4'):
                    $kode = 'HL4';
                    break;

                case str_contains($lower, 'event'):
                    $kode = 'EVT';
                    break;

                case str_contains($lower, 'test'):
                    $kode = 'TST';
                    break;

                /*
                |--------------------------------------------------------------------------
                | SHIFT BERDASARKAN JAM
                |--------------------------------------------------------------------------
                */

                case preg_match(
                    '/^(\d{2})[:\.](\d{2})(?:-(\d{2})[:\.](\d{2}))?$/',
                    trim($nama),
                    $m
                ):

                    // contoh 09.00
                    if (!isset($m[3])) {

                        $kode = $m[1] . $m[2];
                    }

                    // contoh 08.00-16.00
                    elseif (
                        $m[2] == '00' &&
                        $m[4] == '00'
                    ) {

                        $kode = $m[1] . $m[3];
                    }

                    // contoh 07.30-12.30
                    else {

                        $kode =
                            $m[1] .
                            $m[2] .
                            $m[3] .
                            $m[4];
                    }

                    break;

                /*
                |--------------------------------------------------------------------------
                | DEFAULT
                |--------------------------------------------------------------------------
                */

                default:

                    $kode = strtoupper(
                        preg_replace('/[^A-Z0-9]/', '', substr($nama, 0, 4))
                    );

                    if ($kode == '') {
                        $kode = 'SFT';
                    }
            }

            $base = strtoupper($kode);

            $kode = $base;

            $i = 2;

            while (in_array($kode, $used)) {

                $kode = $base . $i;

                $i++;
            }

            $shift->update([
                'kode_shift' => $kode,
            ]);

            $used[] = $kode;

            $this->line(
                str_pad($shift->id, 3, ' ', STR_PAD_LEFT) .
                    ' | ' .
                    str_pad($kode, 10) .
                    ' | ' .
                    $shift->nama_shift
            );
        }

        $this->newLine();
        $this->info('Generate kode shift selesai.');
    }
}
