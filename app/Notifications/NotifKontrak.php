<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class NotifKontrak extends Notification
{
    public function __construct(public array $payload) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type'        => 'kontrak_reminder',
            'karyawan_id' => $this->payload['karyawan_id'],
            'nama'        => $this->payload['nama'],
            'status'      => $this->payload['status'],
            'tgl_keluar'  => $this->payload['tgl_keluar'],
            'sisa_hari'   => $this->payload['sisa_hari'],
        ];
    }
}
