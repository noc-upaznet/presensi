<?php

namespace App\Livewire\Karyawan;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Presensi;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Facades\Crypt;
use App\Livewire\Forms\TambahDataKaryawanForm;
use Livewire\WithPagination;

class JadwalShift extends Component
{
    use WithPagination;

    public TambahDataKaryawanForm $form;

    public $selectedTemplateId;
    public $bulan_tahun;
    public $filterBulan;
    public $kalender = [];
    public $selectedKaryawan;
    public $namaKaryawan;
    public $karyawans;
    public $filterKaryawan;
    public $filterJadwals;

    protected $listeners = [
        'refreshTable' => '$refresh',
        'jadwalAdded' => '$refresh',
        'jadwalUpdated' => '$refresh',
    ];

    public function mount()
    {
        $this->bulan_tahun = now()->format('Y-m');
        $this->filterBulan = now()->format('Y-m');
        $this->karyawans = M_DataKaryawan::orderBy('nama_karyawan')->get();
    }

    public function showAdd()
    {
        $this->form->reset();
        $this->dispatch('modalTambahJadwal', action: 'show');
    }

    public function showEdit($id)
    {
        $jadwal = M_Jadwal::findOrFail(Crypt::decrypt($id));
        $this->bulan_tahun = substr($jadwal->bulan_tahun, 0, 7);
        $this->selectedKaryawan = (string) $jadwal->user_id;

        $this->kalender = [];
        for ($i = 1; $i <= 31; $i++) {
            $field = 'd' . $i;
            if (!is_null($jadwal->$field)) {
                $this->kalender[$i] = $jadwal->$field;
            }
        }

        $this->selectedTemplateId = null;

        $this->dispatch('edit-data', data: $jadwal->toArray());
        $this->dispatch('modalEditJadwal', action: 'show');
    }

    public function showDetail($id)
    {
        $jadwal = M_Jadwal::findOrFail(Crypt::decrypt($id));
        $this->bulan_tahun = substr($jadwal->bulan_tahun, 0, 7);
        $this->selectedKaryawan = (string) $jadwal->user_id;

        $this->kalender = [];
        for ($i = 1; $i <= 31; $i++) {
            $field = 'd' . $i;
            if (!is_null($jadwal->$field)) {
                $this->kalender[$i] = $jadwal->$field;
            }
        }

        $izin = 0;
        $cuti = 0;

        foreach ($this->kalender as $shiftId) {
            if ($shiftId == 3) $izin++;
            if ($shiftId == 2) $cuti++;
        }

        $presensiHadir = [];
        $presensi = M_Presensi::where('user_id', $jadwal->user_id)
            ->whereYear('tanggal', substr($jadwal->bulan_tahun, 0, 4))
            ->whereMonth('tanggal', substr($jadwal->bulan_tahun, 5, 2))
            ->get();

        foreach ($presensi as $p) {
            $day = (int) Carbon::parse($p->tanggal)->format('j');
            $presensiHadir[$day] = $p->status;
        }

        $this->dispatch('detail-data', data: [
            ...$jadwal->toArray(),
            'presensiHadir' => $presensiHadir,
            'rekap' => [
                'izin' => $izin,
                'cuti' => $cuti,
                'terlambat' => $presensi->where('status', 1)->count(),
                'kehadiran' => $presensi->where('status', '!=', '')->count(),
            ],
        ]);

        $this->dispatch('modalDetailJadwal', action: 'show');
    }

    public function delete($id)
    {
        $jadwal = M_Jadwal::findOrFail(Crypt::decrypt($id));
        $jadwal->delete();
        $this->dispatch('modal-confirm-delete', action: 'hide');
    }

    public function updatedFilterBulan()
    {
        $this->resetPage();
    }

    public function filterByKaryawan($karyawanId)
    {
        $this->filterKaryawan = $karyawanId ?: null;
        $this->resetPage();
    }

    public function getJadwalsProperty()
    {
        $entitas = session('selected_entitas', 'UHO');
        $entitasModel = \App\Models\M_Entitas::where('nama', $entitas)->first();

        $query = M_Jadwal::with('getKaryawan');

        if (!empty($this->filterKaryawan)) {
            $query->where('karyawan_id', $this->filterKaryawan);
        }

        if (!empty($this->filterBulan)) {
            $query->where('bulan_tahun', 'like', $this->filterBulan . '%');
        }

        if ($entitasModel) {
            dd($entitasModel);
            $query->whereHas('getKaryawan', function ($q) use ($entitasModel) {
                $q->where('entitas', $entitasModel->id);
            });
        }

        return $query->latest()->paginate(10);
    }

    public function render()
    {
        return view('livewire.karyawan.jadwal-shift', [
            'jadwals' => $this->filteredJadwals,
        ]);
    }
}
