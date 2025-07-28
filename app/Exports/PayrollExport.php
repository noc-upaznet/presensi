<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PayrollExport implements WithMultipleSheets
{
    protected $periode;

    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    public function sheets(): array
    {
        return [
            new PayrollSheet($this->periode, 'tetap'),
            new PayrollSheet($this->periode, 'titip'),
        ];
    }
}
