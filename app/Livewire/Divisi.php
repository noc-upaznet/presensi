<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\M_Divisi;
use Illuminate\Support\Facades\Crypt;
use Livewire\WithPagination;

class Divisi extends Component
{
    use WithPagination;

    public $nama;
    public $deskripsi;
    public $editId;

    // public function store()
    // {
    //     $this->validate([
    //         'nama' => 'required|string|max:255',
    //         'deskripsi' => 'nullable|string|max:500',
    //     ]);

    //     M_Divisi::create([
    //         'nama' => $this->nama,
    //         'deskripsi' => $this->deskripsi,
    //     ]);

    //     // Reset input
    //     $this->reset(['nama', 'deskripsi']);

    //     // Kirim notifikasi
    //     $this->dispatch('swal', params: [
    //         'title' => 'Data Saved',
    //         'icon' => 'success',
    //         'text' => 'Data has been saved successfully'
    //     ]);
        
    //     $this->dispatch('modalAdd', action: 'hide');
    // }

    public function showEditDivisi($id)
    {
        $divisi = M_Divisi::find(Crypt::decrypt($id));
        $this->nama = $divisi->nama;
        $this->deskripsi = $divisi->deskripsi;

        if (!$divisi) {
            session()->flash('error', 'Data tiket tidak ditemukan!');
            return;
        }

        // Kirim data ke komponen
        $this->dispatch('datas', data: $divisi->toArray());
        // Show modal
        $this->dispatch('modalEditDivisi', action: 'show');
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $divisi = M_Divisi::findOrFail($this->editId);
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
        
        $this->dispatch('modalEdit', action: 'hide');
    }

    public function deleteDivisi($id)
    {
        $divisi = M_Divisi::findOrFail(Crypt::decrypt($id));
        $divisi->delete();

        $this->dispatch('swal', params: [
            'title' => 'Data Deleted',
            'icon' => 'success',
            'text' => 'Data has been deleted successfully'
        ]);
    }
    
    public function render()
    {
        $divisies = M_Divisi::orderBy('created_at', 'desc')->latest()->paginate(10);
        return view('livewire.divisi', [
            'divisies' => $divisies,
        ]);
    }
}
