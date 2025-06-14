<?php

namespace App\Livewire\ModalRole;

use Livewire\Component;
use App\Models\M_Entitas;

class ModalEntitas extends Component
{
    public $nama;
    public $alamat;
    public $koordinat;
    public $editId;
    protected $listeners = ['datas' => 'loadDatas'];


    public function store()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'koordinat' =>'nullable|string',
        ]);

        $data = [
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'koordinat' => $this->koordinat
        ];

        M_Entitas::create($data);

        // Reset input
        $this->reset(['nama', 'alamat', 'koordinat']);

        // Kirim notifikasi
        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);
        
        $this->dispatch('refresh');
        $this->dispatch('modalAddEntitas', action: 'hide');
    }

    public function loadDatas($data)
    {
        $this->editId = $data['id'];
        $this->nama = $data['nama'] ?? '';
        $this->alamat = $data['alamat'] ?? '';
        $this->koordinat = $data['koordinat'] ?? '';
    }
    public function update()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'koordinat' => 'nullable|string|max:500',
        ]);

        $entitas = M_Entitas::findOrFail($this->editId);
        $entitas->update([
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'koordinat' => $this->koordinat,
        ]);
        
        // Reset input
        $this->reset(['nama', 'alamat', 'koordinat']);

        // Kirim notifikasi
        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);
        
        $this->dispatch('refresh');
        $this->dispatch('modalEditEntitas', action: 'hide');
    }

    public function render()
    {
        return view('livewire.modal-role.modal-entitas');
    }
}
