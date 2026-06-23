<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportTicketExport implements WithMultipleSheets
{
    protected $reportKunjungan;
    protected $reportTm;

    public function __construct($reportKunjungan, $reportTm)
    {
        $this->reportKunjungan = $reportKunjungan;
        $this->reportTm = $reportTm;
    }

    public function sheets(): array
    {
        return [
            new ReportKunjunganSheet($this->reportKunjungan),
            new ReportTmSheet($this->reportTm),
        ];
    }
}
