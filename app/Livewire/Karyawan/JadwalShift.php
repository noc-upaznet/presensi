<?php

namespace App\Livewire\Karyawan;

use App\Models\User;
use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_DataKaryawan;
use App\Models\M_TemplateWeek;
use Illuminate\Support\Facades\Crypt;
use App\Livewire\Forms\TambahDataKaryawanForm;

class JadwalShift extends Component
{
    public TambahDataKaryawanForm $form;
    
    public $selectedTemplateId;
    public $bulan_tahun;
    public $filterBulan;
    public $kalender = [];
    public $selectedKaryawan;
    public $namaKaryawan;
    public $karyawans;
    public $users;
    public $filterJadwals = [];
    public $filterKaryawan;

    protected $listeners = ['refreshTable' => 'refresh',  'jadwalAdded' => 'onJadwalAdded', 'jadwalUpdated' => 'onJadwalUpdated'];

    public function onJadwalAdded()
    {
        $this->applyFilters();
    }
    public function onJadwalUpdated()
    {
        $this->applyFilters();
    }

    public function showAdd()
    {
        // $this->dispatch('modalTambahJadwal', action: 'show');
        $this->form->reset();
        $this->dispatch('modalTambahJadwal', action: 'show');
    }

    public function showEdit($id)
    {
        $jadwal = M_Jadwal::findOrFail(Crypt::decrypt($id));
        $this->bulan_tahun = substr($jadwal->bulan_tahun, 0, 7); 
        // dd($this->bulan_tahun);
        $this->selectedKaryawan = (string) $jadwal->user_id;

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
        $this->dispatch('modal-confirm-delete', action: 'hide');
    }

    public function mount()
    {
        $this->bulan_tahun = now()->format('Y-m');
        $this->filterBulan = now()->format('Y-m');
        // $this->karyawans = M_DataKaryawan::orderBy('nama_karyawan')->get();
        $this->users = User::where('role', 'user')->orderBy('name')->get();
        $this->applyFilters();
    }

    public function applyFilters()
    {
        $query = M_Jadwal::with('getUser');

        if (!empty($this->filterKaryawan)) {
            $query->where('user_id', $this->filterKaryawan);
        }

        if (!empty($this->filterBulan)) {
            $query->where('bulan_tahun', 'like', $this->filterBulan . '%');
        }

        $this->filterJadwals = $query->get();
    }

    public function updatedFilterBulan()
    {
        $this->applyFilters();
    }

    public function filterByKaryawan($karyawanId)
    {
        $this->filterKaryawan = $karyawanId ?: null;
        $this->applyFilters();
    }

    public function render()
    {
        return view('livewire.karyawan.jadwal-shift', [
            'jadwals' => $this->filterJadwals,
        ]);
    }
}
