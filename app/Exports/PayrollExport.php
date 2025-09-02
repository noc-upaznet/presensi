<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PayrollExport implements WithMultipleSheets
{
    protected $periode;
    protected $entitas;

    public function __construct($periode, $entitas)
    {
        // dd($entitas);
        $this->periode = $periode;
        $this->entitas = $entitas;
    }

    public function sheets(): array
    {
        return [
            new PayrollSheet($this->periode, 'tetap', $this->entitas),
            new PayrollSheet($this->periode, 'titip', $this->entitas),
        ];
    }
}
