<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JenisPotonganModel;

class JenisPotongan extends Component
{
    public $jenisPotongan = [];
    public $nama_potongan, $deskripsi;
    public $edit_id = null; // simpan id untuk edit

    public $listeners = ['refreshTable' => '$refresh'];

    public function mount()
    {
        $this->jenisPotongan = JenisPotonganModel::all();
    }

    public function store()
    {
        $this->validate([
            'nama_potongan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        JenisPotonganModel::create([
            'nama_potongan' => $this->nama_potongan,
            'deskripsi' => $this->deskripsi,
        ]);

        $this->reset(['nama_potongan', 'deskripsi']);
        $this->jenisPotongan = JenisPotonganModel::all();

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);
        
        $this->dispatch('tambahJenisPotonganModal', action: 'hide');   
    }

    public function editPotongan($id)
    {
        $potongan = JenisPotonganModel::findOrFail($id);

        $this->edit_id = $id;
        $this->nama_potongan = $potongan->nama_potongan;
        $this->deskripsi = $potongan->deskripsi;

        $this->dispatch('editPotonganModal', action: 'show');
    }

    public function update()
    {
        $this->validate([
            'nama_potongan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        if ($this->edit_id) {
            $potongan = JenisPotonganModel::findOrFail($this->edit_id);

            $potongan->update([
                'nama_potongan' => $this->nama_potongan,
                'deskripsi' => $this->deskripsi,
            ]);
        }

        $this->reset(['nama_potongan', 'deskripsi', 'edit_id']);
        $this->jenisPotongan = JenisPotonganModel::all();

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        $this->dispatch('editPotonganModal', action: 'hide');
    }

    public function render()
    {
        return view('livewire.jenis-potongan', [
            'jenisPotongan' => $this->jenisPotongan,
        ]);
    }
}