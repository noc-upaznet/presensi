<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketKunjunganRepeatExport implements FromArray, WithHeadings
{
    public function __construct(
        protected $tickets
    ) {}

    public function headings(): array
    {
        return [
            'No Tiket',
            'Tanggal',
            'ID Pelanggan',
            'Nama Pelanggan',
            'Keterangan',
            'Keterangan Tambahan',
            'Team',
            'Status',
            '1 Bulan',
            '1 Minggu',
        ];
    }

    public function array(): array
    {
        $status = [
            '0' => '',
            '1' => 'ON-PROGRESS',
            '2' => 'PENDING',
            '3' => 'CANCEL',
            '4' => 'REPORTED',
            '5' => 'LOST-TIME',
            '6' => 'CHECK',
            '7' => 'RESCHEDULE',
            '8' => 'CONFIRM',
            '9' => 'DONE',
        ];

        return $this->tickets->map(function ($ticket) use ($status) {

            return [
                $ticket->ticket_number,
                $ticket->created_at,
                $ticket->customer->registration_number ?? '',
                $ticket->customer->name ?? '',
                strip_tags($ticket->description),
                strip_tags($ticket->additional),
                $ticket->team->name ?? '',
                $status[$ticket->status] ?? '',
                $ticket->repeat_month,
                $ticket->repeat_week,
            ];
        })->toArray();
    }
}
