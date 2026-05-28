<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;

class PengajuanForm extends Form
{
    public $id;

    #[Validate('required', message: 'Pengajuan wajib dipilih')]
    public $pengajuan = '';
    public $tanggal = '';

    #[Validate('required', message: 'Keterangan wajib diisi')]
    public $keterangan = '';

    public $dates = [
        ['tanggal' => null]
    ];

    public function rules()
    {
        return [
            'dates' => 'required|array|min:1',
            'dates.*.tanggal' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'dates.required' => 'Tanggal pengajuan wajib diisi.',
            'dates.min' => 'Minimal satu tanggal harus dipilih.',

            'dates.*.tanggal.required' => 'Tanggal wajib diisi.',
            'dates.*.tanggal.date' => 'Format tanggal tidak valid.',
        ];
    }
}
