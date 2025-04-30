<?php

namespace App\Livewire\Karyawan\Shifts;

use Livewire\Component;
use App\Models\M_JadwalShift;
use App\Livewire\Forms\TambahTemplateMingguan;
use App\Models\M_TemplateWeek;

class ModalTemplate extends Component
{
    public TambahTemplateMingguan $form;

    public $jadwalShifts;
    public $templates;
    public $id;
    protected $listeners = ['edit-template' => 'loadData'];

    public function mount()
    {
        $this->jadwalShifts = M_JadwalShift::orderBy('nama_shift')->get();
    }

    public function store()
    {
        $this->form->validate();
        // dd($this->form);
        $data = [
            'nama_template' => $this->form->nama_template,
            'minggu' => $this->form->minggu,
            'senin' => $this->form->senin,
            'selasa' => $this->form->selasa,
            'rabu' => $this->form->rabu,
            'kamis' => $this->form->kamis,
            'jumat' => $this->form->jumat,
            'sabtu' => $this->form->sabtu,
        ];

        // dd($data);
        M_TemplateWeek::create($data);

        $this->form->reset();

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);

        $this->dispatch('modal-tambah-template', action: 'show');
        $this->dispatch('refresh');
    }

    public function loadData($data)
    {
        // dd($data);
        $this->id = $data['id'];
        $this->form->fill($data);
        // $this->form->minggu = $data['minggu'] ?? '';
        // dd($this->form->shift_minggu);

    }

    public function saveEdit()
    {
        $this->form->validate();
        // dd($this->form);
        $data = [
            'nama_template' => $this->form->nama_template,
            'minggu' => $this->form->minggu,
            'senin' => $this->form->senin,
            'selasa' => $this->form->selasa,
            'rabu' => $this->form->rabu,
            'kamis' => $this->form->kamis,
            'jumat' => $this->form->jumat,
            'sabtu' => $this->form->sabtu,
        ];

        // dd($data);
        M_TemplateWeek::find($this->id)->update($data);

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        $this->dispatch('modal-edit-template', action: 'hide',);
        $this->dispatch('refresh');
    }
    public function render()
    {
        return view('livewire.karyawan.shifts.modal-template');
    }
}
