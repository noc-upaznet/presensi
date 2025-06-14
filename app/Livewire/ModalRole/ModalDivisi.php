<?php

namespace App\Livewire\ModalRole;

use Livewire\Component;
use App\Models\M_Divisi;

class ModalDivisi extends Component
{
    public $nama;
    public $deskripsi;
    public $editId;

    protected $listeners = ['datas' => 'loadDatas'];

    public function storeDivisi()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        M_Divisi::create([
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
        ]);

        $this->reset(['nama', 'deskripsi']);

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);
        
        $this->dispatch('refresh');
        $this->dispatch('modalAddDivisi', action: 'hide');
    }

    public function loadDatas($data)
    {
        $this->editId = $data['id'];
        $this->nama = $data['nama'] ?? '';
        // dd($data);
        $this->deskripsi = $data['deskripsi'] ?? '';
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $divisi = M_Divisi::findOrFail($this->editId);
        // dd($divisi);
        $divisi->update([
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
        ]);

        // Reset input
        $this->reset(['nama', 'deskripsi']);

        // Kirim notifikasi
        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);
        
        $this->dispatch('refresh');
        $this->dispatch('modalEditDivisi', action: 'hide');
    }
    
    public function render()
    {
        return view('livewire.modal-role.modal-divisi');
    }
}
