<?php

namespace App\Livewire;

use Livewire\Component;

class NotifikasiBell extends Component
{
    public $notifs = [];
    public $unreadCount = 0;
    public $showDropdown = false;

    protected $listeners = ['echo:pengajuan,pengajuan-baru' => 'tambahNotif'];

    public function tambahNotif($payload)
    {
        $pengajuan = $payload['pengajuan'];

        $this->notifs[] = [
            'nama' => $pengajuan['nama'],
            'jenis' => $pengajuan['jenis'],
            'created_at' => $pengajuan['created_at'],
        ];

        $this->unreadCount++;
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
        $this->unreadCount = 0;
    }
    public function render()
    {
        return view('livewire.notifikasi-bell');
    }
}
