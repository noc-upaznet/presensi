<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\M_Jabatan;
use Illuminate\Support\Facades\Crypt;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Jabatan extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public $nama_jabatan;
    public $deskripsi;
    public $has_staff = false; // Default value
    public $spv_id = false; // Supervisor ID
    public $editId;
    
    public function showEdit($id)
    {
        $jabatan = M_Jabatan::find(Crypt::decrypt($id));
        $this->nama_jabatan = $jabatan->nama_jabatan;
        $this->deskripsi = $jabatan->deskripsi;
        $this->has_staff = (bool) $jabatan->has_staff;
        $this->spv_id = (bool) $jabatan->spv_id;

        if (!$jabatan) {
            session()->flash('error', 'Data tiket tidak ditemukan!');
            return;
        }

        // Kirim data ke komponen
        $this->dispatch('datas', data: $jabatan->toArray());
        // Show modal
        $this->dispatch('modalEdit', action: 'show');
    }

    public function delete($id)
    {
        $jadwal = M_Jabatan::findOrFail(Crypt::decrypt($id));
        // dd($jadwal);
        $jadwal->delete();
        $this->dispatch('swal', params: [
            'title' => 'Data Deleted',
            'icon' => 'success',
            'text' => 'Data has been deleted successfully'
        ]);
    }
    
    public function render()
    {
        $jabatans = M_Jabatan::orderBy('created_at', 'desc')->latest()->paginate(10);
        return view('livewire.jabatan', [
            'jabatans' => $jabatans,
        ]);
    }
}
