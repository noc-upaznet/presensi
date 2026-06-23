<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportKunjunganSheet implements FromArray, WithHeadings, WithTitle
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
            'ID Pelanggan',
            'Nama Pelanggan',
            'Tiket Dibuat',
            'Waktu Report',
            'Lama Pengerjaan',
            'Team',
            'Teknisi',
            'Detail Pengerjaan',
            'Data Berubah',
            'Barang Digunakan',
            'Biaya',
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
                $report->ticket->customer->registration_number ?? '-',
                $report->ticket->customer->name ?? '-',
                $report->ticket->created_at,
                $report->created_at,
                $diff->format('%a Hari %h Jam %i Menit'),
                $report->team->name ?? '-',
                $report->teknisi,
                strip_tags($report->detail_report),
                strip_tags($report->changed_data),
                strip_tags($report->goods),
                $report->bill,
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
        return 'Kunjungan';
    }
}
