<?php

namespace App\Livewire\Navigation;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Navbar extends Component
{
    public $notifs = [];
    public $unreadCount = 0;

    protected $listeners = ['echo:pengajuan,pengajuan-baru' => 'tambahNotif'];

    public function tambahNotif($payload)
    {
        // Log::info('Data diterima oleh Livewire:', $payload);
        $pengajuan = $payload['pengajuan'];
        // dd($pengajuan);
        array_unshift($this->notifs, [
            'nama_karyawan' => $pengajuan['karyawan_id'],
            'nama_shift' => $pengajuan['shift_id'],
            'created_at' => \Carbon\Carbon::parse($pengajuan['created_at'])->diffForHumans(),
        ]);

        $this->unreadCount++;
    }

    public function render()
    {
        return view('livewire.navigation.navbar');
    }
}
