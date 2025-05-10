<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\PengajuanBaruEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\PushDemo;

class KirimNotifikasiPengajuan
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PengajuanBaruEvent $event): void
    {
        // Kirim notifikasi ke semua admin (contoh)
        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new PushDemo("Pengajuan baru dari karyawan ID: {$event->pengajuan['karyawan_id']}"));
        }
    }
}
