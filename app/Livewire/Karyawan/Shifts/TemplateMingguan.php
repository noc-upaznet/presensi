<?php

namespace App\Livewire\Karyawan\Shifts;

use Livewire\Component;
use App\Models\M_TemplateWeek;
use Illuminate\Support\Facades\Crypt;
use App\Livewire\Forms\TambahTemplateMingguan;

class TemplateMingguan extends Component
{
    public TambahTemplateMingguan $form;
    protected $listeners = ['refreshTable' => '$refresh'];

    public function showAdd()
    {
        // Dispatch event ke modal
        $this->dispatch('modal-tambah-template', action: 'show');
    }

    public function showEdit($id)
    {
        $this->form->resetValidation();
        $dataTemplate = M_TemplateWeek::find(Crypt::decrypt($id));
        // dd($dataTemplate);
        if (!$dataTemplate) {
            session()->flash('error', 'Data tiket tidak ditemukan!');
            return;
        }

        // Kirim data ke komponen ModalKunjungan
        $this->dispatch('edit-template', data: $dataTemplate->toArray());
        // dd($this->dispatch('edit-template', data: $dataKaryawan->toArray()));

        // Dispatch event ke modal
        $this->dispatch('modal-edit-template', action: 'show',);
    }

    public function delete($id)
    {
        $jadwal = M_TemplateWeek::findOrFail(Crypt::decrypt($id));
        // dd($jadwal);
        $jadwal->delete();
        $this->dispatch('modal-confirm-delete', action: 'hide');
    }

    public function render()
    {
        $datas = M_TemplateWeek::with(
            'getMinggu', 'getSenin', 'getSelasa', 'getRabu', 'getKamis', 'getJumat', 'getSabtu'
        )->latest()->get();
        // dd($datas);
        return view('livewire.karyawan.shifts.template-mingguan', [
            'datas' => $datas,
        ]);
    }
}
