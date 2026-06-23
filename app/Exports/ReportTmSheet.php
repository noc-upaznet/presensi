<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportTmSheet implements FromArray, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'No Tiket',
            'Tiket Dibuat',
            'Waktu Report',
            'Lama Pengerjaan',
            'Team',
            'Teknisi',
            'Detail Pengerjaan',
            'Barang Digunakan',
            'Status',
        ];
    }

    public function array(): array
    {
        return $this->data->map(function ($report) {

            $diff = Carbon::parse($report->ticket->created_at)
                ->diff(Carbon::parse($report->created_at));

            return [
                $report->ticket->ticket_number,
                $report->ticket->created_at,
                $report->created_at,
                $diff->format('%a Hari %h Jam %i Menit'),
                $report->team->name ?? '-',
                $report->teknisi,
                strip_tags($report->detail_report),
                strip_tags($report->goods),
                match ($report->status) {
                    1 => 'Done',
                    2 => 'Lost Time',
                    3 => 'Pending',
                    default => 'Unknown'
                }
            ];
        })->toArray();
    }

    public function title(): string
    {
        return 'T&M';
    }
}
