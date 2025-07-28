<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Facades\Crypt;

class DetailDataKaryawan extends Component
{
    public $jml_poin;

    public $id;
    public $karyawan;

    public function mount($id)
    {
        $this->id = Crypt::decrypt($id);
        $this->karyawan = M_DataKaryawan::find($this->id);

        if (!$this->karyawan) {
            abort(404);
        }
    }
    public function updateGamifikasi()
    {
        $this->validate([
            'jml_poin' => 'required|numeric|min:0',
        ]);

        // Ambil poin lama lalu tambahkan
        $poinLama = $this->karyawan->poin ?? 0;
        $poinBaru = $poinLama + $this->jml_poin;

        $this->karyawan->update([
            'poin' => $poinBaru,
        ]);
        $this->reset('jml_poin');

        $this->dispatch('swal', params: [
            'title' => 'Poin Berhasil Ditambahkan',
            'icon' => 'success',
            'text' => "Poin bertambah, Total sekarang: $poinBaru"
        ]);
    }

    public function render()
    {
        return view('livewire.karyawan.detail-data-karyawan');
    }
}
