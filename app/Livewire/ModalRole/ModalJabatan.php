<?php

namespace App\Livewire\ModalRole;

use App\Models\Lokasi;
use Livewire\Component;
use App\Models\M_Jabatan;

class ModalJabatan extends Component
{
    public $jabatan = [];
    public $nama_jabatan;
    public $deskripsi;
    public $has_staff = false; // Default value
    public $spv_id = false; // Supervisor ID
    public $editId;
    public $listeners = ['datas' => 'loadDatas'];
    
    public function store()
    {
        $this->validate([
            'nama_jabatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'has_staff' => 'boolean',
            'spv_id' => 'boolean', // Assuming spv_id refers to an existing jabatan
        ]);
        $data = [
            'nama_jabatan' => $this->nama_jabatan,
            'deskripsi' => $this->deskripsi,
            'has_staff' => $this->has_staff,
            'spv_id' => $this->spv_id,
        ];
        // dd($data);
        M_Jabatan::create($data);        

        // Reset input
        $this->reset(['nama_jabatan', 'deskripsi', 'has_staff', 'spv_id']);
        $this->jabatan = M_Jabatan::all();


        // Refresh data lokasi
        // $this->lokasi_list = Lokasi::all();

        // Kirim notifikasi (opsional)
        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been Saved successfully'
        ]);

        $this->dispatch('modalAdd', action: 'hide');
    }

    public function loadDatas($data)
    {
        $this->editId = $data['id'];
        $this->nama_jabatan = $data['nama_jabatan'] ?? '';
        $this->deskripsi = $data['deskripsi'] ?? '';
        $this->has_staff = in_array(strtolower($data['has_staff'] ?? ''), ['1', 'iya', 'ya', 'true', 1], true);
        $this->spv_id = in_array(strtolower($data['spv_id'] ?? ''), ['1', 'iya', 'ya', 'true', 1], true);
    }

    public function update()
    {
        $this->validate([
            'nama_jabatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'has_staff' => 'boolean',
            'spv_id' => 'boolean', // Assuming spv_id refers to an existing jabatan
        ]);
        
        $jabatan = M_Jabatan::findOrFail($this->editId);
        $jabatan->update([
            'nama_jabatan' => $this->nama_jabatan,
            'deskripsi' => $this->deskripsi,
            'has_staff' => $this->has_staff,
            'spv_id' => $this->spv_id,
        ]);

        $this->reset(['nama_jabatan', 'deskripsi', 'has_staff', 'spv_id']);

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        $this->dispatch('refresh');
        $this->dispatch('modalEdit', action: 'hide');
    }

    public function render()
    {
        return view('livewire.modal-role.modal-jabatan');
    }
}
