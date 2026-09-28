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

        foreach ($this->jadwals as $jadwal) {

            // Normalisasi format jam
            $jamMasuk = str_replace('.', ':', trim($jadwal['jam_masuk']));
            $jamPulang = str_replace('.', ':', trim($jadwal['jam_pulang']));

            // Pastikan format HH:MM
            $jamMasuk = date('H:i', strtotime($jamMasuk));
            $jamPulang = date('H:i', strtotime($jamPulang));

            [$jamMasukHour, $jamMasukMinute] = explode(':', $jamMasuk);
            [$jamPulangHour, $jamPulangMinute] = explode(':', $jamPulang);

            if ($jamMasukMinute === '00' && $jamPulangMinute === '00') {

                $kodeShift = $jamMasukHour . $jamPulangHour;
            } else {

                $kodeShift =
                    $jamMasukHour .
                    $jamMasukMinute .
                    $jamPulangHour .
                    $jamPulangMinute;
            }

            M_JadwalShift::create([
                'nama_shift' => $jadwal['nama_shift'],
                'jam_masuk' => $jamMasuk,
                'jam_pulang' => $jamPulang,
                'kode_shift' => $kodeShift,
            ]);
        }

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
