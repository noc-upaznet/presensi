<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JenisTunjanganModel;

class JenisTunjangan extends Component
{
    public $jenisTunjangan = [];
    public $nama_tunjangan, $deskripsi;
    Public $listeners = ['refreshTable' => '$refresh'];

    public function mount()
    {
        $this->jenisTunjangan = JenisTunjanganModel::all();
    }

    public function store()
    {
        $this->validate([
            'nama_tunjangan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        JenisTunjanganModel::create([
            'nama_tunjangan' => $this->nama_tunjangan,
            'deskripsi' => $this->deskripsi,
        ]);

        $this->reset(['nama_tunjangan', 'deskripsi']);
        $this->jenisTunjangan = JenisTunjanganModel::all();

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);
        
        $this->dispatch('tambahJenisTunjanganModal', action: 'hide');   
    }

    public function render()
    {
        return view('livewire.jenis-tunjangan', [
            'jenisTunjangan' => $this->jenisTunjangan,
        ]);
    }
}