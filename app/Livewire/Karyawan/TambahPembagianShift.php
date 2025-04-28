<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\M_JadwalShift;

class TambahPembagianShift extends Component
{
    public $nama_shift = '';
    public $jam_masuk = '';
    public $jam_pulang = '';

    public $jadwals = [
        ['nama_shift' => '', 'jam_masuk' => '', 'jam_pulang' => ''],
    ];
    
    public function tambahJadwal()
    {
        $this->jadwals[] = ['nama_shift' => '', 'jam_masuk' => '', 'jam_pulang' => ''];
    }
    
    public function hapusJadwal($index)
    {
        unset($this->jadwals[$index]);
        $this->jadwals = array_values($this->jadwals); // reset index
    }

    public function store()
    {
        $this->validate([
            'jadwals.*.nama_shift' => 'required',
            'jadwals.*.jam_masuk' => 'required',
            'jadwals.*.jam_pulang' => 'required',
        ]);
        // dd($this->jadwals);
        foreach ($this->jadwals as $jadwal) {
            M_JadwalShift::create([
                'nama_shift' => $jadwal['nama_shift'],
                'jam_masuk' => $jadwal['jam_masuk'],
                'jam_pulang' => $jadwal['jam_pulang'],
            ]);
        }
    
        // $this->form->reset();
        $this->reset('jadwals');
    
        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);

    }

    public function render()
    {
        return view('livewire.karyawan.tambah-pembagian-shift', [
            'shifts' => M_JadwalShift::all(),
        ]);
    }
}
