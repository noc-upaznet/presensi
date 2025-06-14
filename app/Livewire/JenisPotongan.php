<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JenisPotonganModel;

class JenisPotongan extends Component
{
    public $jenisPotongan = [];
    public $nama_potongan, $deskripsi;

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

    public function render()
    {
        return view('livewire.jenis-potongan', [
            'jenisPotongan' => $this->jenisPotongan,
        ]);
    }
}