<?php

namespace App\Livewire\ModalRole;

use App\Models\M_Roles;
use Livewire\Component;

class ModalRoles extends Component
{
    public $nama;
    public function store()
    {
        // Validasi input
        $this->validate([
            'nama' => 'required|string|max:255',
            // 'deskripsi' => 'nullable|string|max:500',
        ]);

        $data = [
            'nama' => $this->nama,
            // 'deskripsi' => $this->deskripsi,
        ];
        // dd($data);
        // Simpan data role baru
        M_Roles::create($data);

        // Reset input
        $this->reset(['nama']);

        // Kirim notifikasi sukses
        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been Saved successfully'
        ]);

        // Refresh data di komponen yang memanggil modal ini
        $this->dispatch('refresh');
        $this->dispatch('modalAddRoles', action: 'hide');
    }
    public function render()
    {
        return view('livewire.modal-role.modal-roles');
    }
}
