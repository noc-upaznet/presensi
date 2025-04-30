<?php

namespace App\Livewire\Karyawan;

use App\Livewire\Forms\TambahDataKaryawanForm;
use Livewire\Component;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Facades\Crypt;

class DataKaryawan extends Component
{
    public TambahDataKaryawanForm $form;

    protected $listeners = ['refreshTable' => '$refresh'];

    public function showEdit($id)
    {
        $this->form->resetValidation();
        $dataKaryawan = M_DataKaryawan::find(Crypt::decrypt($id));
        if (!$dataKaryawan) {
            session()->flash('error', 'Data tiket tidak ditemukan!');
            return;
        }

        // Kirim data ke komponen ModalKunjungan
        $this->dispatch('edit-ticket', data: $dataKaryawan->toArray());
        // dd($this->dispatch('edit-ticket', data: $dataKaryawan->toArray()));

        // Dispatch event ke modal
        $this->dispatch('modal-edit-data-karyawan', action: 'show');
    }

    public function showModalImport()
    {
        $this->dispatch('modal-import', action: 'show');
    }

    public function DetailDataKaryawan($id)
    {
        return redirect()->route('karyawan.detail-data-karyawan', ['id' => $id]);
    }

    public function render()
    {
        $datas = M_DataKaryawan::latest()->get();
        return view('livewire.karyawan.data-karyawan', [
            'datas' => $datas,
        ]);
    }
}
