<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\M_Entitas;
use Illuminate\Support\Facades\Crypt;
use Livewire\WithPagination;

class Entitas extends Component
{
    use WithPagination;

    public $nama;
    public $deskripsi;
    public $editId;

    public function showEditEntitas($id)
    {
        // $this->editId = Crypt::decrypt($id);
        $entitas = M_Entitas::find(Crypt::decrypt($id));
        $this->nama = $entitas->nama;
        $this->deskripsi = $entitas->deskripsi;

        if (!$entitas) {
            session()->flash('error', 'Data tiket tidak ditemukan!');
            return;
        }

        // Kirim data ke komponen
        $this->dispatch('datas', data: $entitas->toArray());

        // Show modal
        $this->dispatch('modalEditEntitas', action: 'show');
    }

    public function deleteEntitas($id)
    {
        $divisi = M_Entitas::findOrFail(Crypt::decrypt($id));
        $divisi->delete();

        $this->dispatch('swal', params: [
            'title' => 'Data Deleted',
            'icon' => 'success',
            'text' => 'Data has been deleted successfully'
        ]);
    }

    public function render()
    {
        $entitas = M_Entitas::orderBy('created_at', 'desc')->latest()->paginate(10);
        return view('livewire.entitas', [
            'entitas' => $entitas,
        ]);}
}
