<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_DataKaryawan;
use App\Models\M_TemplateWeek;
use Illuminate\Support\Facades\Crypt;

class JadwalShift extends Component
{
    public $selectedTemplateId;
    public $bulan_tahun;
    public $kalender = []; // format: ['minggu' => '', 'senin' => '', dst] 
    public $selectedKaryawan;
    public $namaKaryawan;
    public $karyawans;

    protected $listeners = ['refreshTable' => '$refresh',  ];
    // public $dataKaryawans;
    public function showAdd()
    {
        $this->dispatch('modalTambahJadwal', action: 'show');
    }

    public function showEdit($id)
    {
        $jadwal = M_Jadwal::findOrFail(Crypt::decrypt($id));
        $this->bulan_tahun = substr($jadwal->bulan_tahun, 0, 7); 
        // dd($this->bulan_tahun);
        $this->selectedKaryawan = (string) $jadwal->id_karyawan;

        // Load shift harian
        $this->kalender = [];
        for ($i = 1; $i <= 31; $i++) {
            $field = 'd' . $i;
            if (!is_null($jadwal->$field)) {
                $this->kalender[$i] = $jadwal->$field;
            }
        }
        $this->selectedTemplateId = null;

        $this->dispatch('edit-data', data: $jadwal->toArray());
        // dd($this->bulan_tahun, $this->namaKaryawan, $this->kalender);


        $this->dispatch('modalEditJadwal', action: 'show');
    }

    public function delete($id)
    {
        $jadwal = M_Jadwal::findOrFail(Crypt::decrypt($id));
        // dd($jadwal);
        $jadwal->delete();
        session()->flash('success', 'Jadwal berhasil dihapus');
        $this->dispatch('modalEditJadwal', action: 'hide');
    }

    public function mount()
    {
        $this->bulan_tahun = now()->format('Y-m');
        $this->karyawans = M_DataKaryawan::orderBy('nama_karyawan')->get();
        // $this->jadwalShifts = M_JadwalShift::orderBy('nama_shift')->get();
        // $this->templateWeeks = M_TemplateWeek::orderBy('nama_template')->get();

    }

    public function render()
    {
        $jadwals = M_Jadwal::with('getKaryawan')->latest()->get();
        return view('livewire.karyawan.jadwal-shift', [
            'jadwals' => $jadwals,
        ]);
    }
}
