<?php

namespace App\Livewire\Karyawan;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Presensi;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Livewire\Forms\TambahDataKaryawanForm;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class JadwalShift extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
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

    public function mount()
    {
        $this->bulan_tahun = now()->format('Y-m');
        $this->filterBulan = now()->format('Y-m');
        $user = Auth::user();
        $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
        // $jadwalId = M_Jadwal::where('bulan_tahun', $this->bulan_tahun)
        //     ->pluck('karyawan_id')
        //     ->toArray();

        if ($user->hasAnyRole('spv-teknisi|spv-helpdesk')) {
            $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
            $divisi = $karyawan->divisi;
            // dd($divisi);
            $entitas = $karyawan->entitas;
            // $entitas = session('selected_entitas', 'UHO');

            $this->karyawans = M_DataKaryawan::where('entitas', $entitas)
                ->where('divisi', $divisi)
                // ->whereNotIn('id', $jadwalId)
                ->orderBy('nama_karyawan')
                ->get();
        } elseif ($user->hasAnyRole('admin|hr')) {
            $entitas = session('selected_entitas', 'UHO');

            $this->karyawans = M_DataKaryawan::where('entitas', $entitas)
                // ->whereNotIn('id', $jadwalId)
                ->orderBy('nama_karyawan')
                ->get();
        } elseif ($user->hasRole('user')) {
            $divisi = $karyawan->divisi;
            $entitas = $karyawan->entitas;
            $this->karyawans = M_DataKaryawan::where('entitas', $entitas)
                ->orderBy('nama_karyawan')
                ->get();
        }
        $this->applyFilters();
    }

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

    public function showDetail($id)
    {
        $jadwal = M_Jadwal::findOrFail(Crypt::decrypt($id));
        $this->bulan_tahun = substr($jadwal->bulan_tahun, 0, 7);
        $this->selectedKaryawan = (string) $jadwal->karyawan_id;

        // Load shift harian
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
            // Asumsikan shiftId 99 = Izin, 98 = Cuti (ubah sesuai ID shift kamu)
            if ($shiftId == 3) $izin++;
            if ($shiftId == 2) $cuti++;
        }

        // Ambil data presensi
        $presensiHadir = [];
        $presensi = M_Presensi::where('user_id', $jadwal->karyawan_id)
            ->whereYear('tanggal', substr($jadwal->bulan_tahun, 0, 4))
            ->whereMonth('tanggal', substr($jadwal->bulan_tahun, 5, 2))
            ->get();
        // dd($presensi);
        foreach ($presensi as $p) {
            $day = (int) Carbon::parse($p->tanggal)->format('j'); // tanggal 1-31 sebagai integer
            $presensiHadir[$day] = $p->status;
        }

        // Kirim data jadwal + presensi
        $this->dispatch('detail-data', data: [
            ...$jadwal->toArray(),
            'presensiHadir' => $presensiHadir,
            'rekap' => [
                'izin' => $izin,
                'cuti' => $cuti,
                'terlambat' => $presensi->where('status', 1)->count(), // bisa dikosongkan jika belum ada
                'kehadiran' => $presensi->where('status', '!=', '')->count(),
            ],
        ]);

        $this->dispatch('modalDetailJadwal', action: 'show');
    }

    public function delete($id)
    {
        $jadwal = M_Jadwal::findOrFail(Crypt::decrypt($id));
        // dd($jadwal);
        $jadwal->delete();
        $this->dispatch('modal-confirm-delete', action: 'hide');
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

    public function applyFilters()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = M_Jadwal::with('getKaryawan');
        $user = Auth::user();
        $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
        // dd($karyawan);
        if (!empty($this->filterKaryawan)) {
            $query->where('karyawan_id', $this->filterKaryawan);
        }

        if (!empty($this->filterBulan)) {
            $query->where('bulan_tahun', 'like', $this->filterBulan . '%');
        }

        if ($user->hasAnyRole('spv-teknisi|spv-helpdesk')) {
            $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
            $divisi = $karyawan->divisi;
            $entitas = $karyawan->entitas;
            // dd($entitas);

            $query->whereHas('getKaryawan', function ($q) use ($divisi, $entitas) {
                $q->where('divisi', $divisi)
                    ->where('entitas', $entitas);
            });
        } elseif ($user->hasAnyRole('admin|hr')) {
            $entitas = session('selected_entitas', 'UHO');

            $query->whereHas('getKaryawan', function ($q) use ($entitas) {
                $q->where('entitas', $entitas);
            });
        } elseif ($user->hasRole('user') && $karyawan->entitas == 'MC') {
            $divisi = $karyawan->divisi;
            $entitas = $karyawan->entitas;
            $query->whereHas('getKaryawan', function ($q) use ($divisi, $entitas) {
                $q->where('divisi', $divisi)
                    ->where('entitas', $entitas);
            });
        } elseif ($user->hasRole('user') && $karyawan->entitas == 'UGR') {
            $divisi = $karyawan->divisi;
            $entitas = $karyawan->entitas;
            $query->whereHas('getKaryawan', function ($q) use ($divisi, $entitas) {
                $q->where('divisi', $divisi)
                    ->where('entitas', $entitas);
            });
        }
        $jadwals = $query->paginate(10);
        return view('livewire.karyawan.jadwal-shift', [
            'jadwals' => $jadwals,
        ]);
    }
}
