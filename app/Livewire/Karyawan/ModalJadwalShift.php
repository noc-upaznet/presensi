<?php

namespace App\Livewire\Karyawan;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Presensi;
use App\Models\M_JadwalShift;
use App\Models\M_DataKaryawan;
use App\Models\M_TemplateWeek;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ModalJadwalShift extends Component
{
    public $karyawans;
    public $users;
    public $jadwalShifts;
    public $templateWeeks;
    public $bulan;
    public $bulan_tahun;
    public $selectedKaryawan;
    public $jadwal_id;
    public $namaKaryawan;

    public $selectedTemplateId;
    public $kalender = []; // format: ['minggu' => '', 'senin' => '', dst]
    public $kalenderVersion = 0;
    public $rekap = [];
    public $presensiHadir = [];

    // public $userId;

    protected $listeners = ['setKaryawan', 'bulanChanged' => 'setBulan', 'edit-data' => 'loadData', 'detail-data' => 'loadDetail'];

    public function getBulanTahunProperty()
    {
        $carbon = Carbon::parse($this->bulan_tahun);

        return [
            'bulan' => (int) $carbon->format('m'),
            'tahun' => (int) $carbon->format('Y'),
        ];
    }

    public function setBulan($value)
    {
        $this->bulan = $value;
    }

    public function updatedBulanTahun()
    {
        $user = Auth::user();

        // Ambil ID karyawan yang sudah punya jadwal di bulan-tahun tersebut
        $jadwalId = M_Jadwal::where('bulan_tahun', $this->bulan_tahun)
            ->pluck('karyawan_id')
            ->toArray();
            // dd($jadwalId);
        
        if ($user->hasRole('spv')) {    
            $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
            $divisi = $karyawan->divisi;
            $entitas = $karyawan->entitas;
            $this->karyawans = M_DataKaryawan::where('divisi', $divisi)
                ->where('entitas', $entitas)
                ->whereNotIn('id', $jadwalId)
                ->orderBy('nama_karyawan')
                ->get();
        } elseif ($user->hasRole('admin')) {
            $entitas = session('selected_entitas', 'UHO');
            $this->karyawans = M_DataKaryawan::where('entitas', $entitas)
                ->whereNotIn('id', $jadwalId)
                ->orderBy('nama_karyawan')
                ->get();
        }

        $this->selectedKaryawan = null;
    }


    public function fillCalendarFromTemplate()
    {
        if (empty($this->selectedTemplateId)) {
            // Kalau tidak ada template yang dipilih, kosongkan kalender
            $this->kalender = [];
            return;
        }

        $template = M_TemplateWeek::find($this->selectedTemplateId);
        if (!$template) return;

        if (!$this->bulan_tahun) {
            $this->bulan_tahun = now()->format('Y-m');
        }

        [$tahun, $bulan] = explode('-', $this->bulan_tahun);
        $totalHari = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;

        $this->kalender = [];

        for ($tanggal = 1; $tanggal <= $totalHari; $tanggal++) {
            $tanggalCarbon = Carbon::createFromDate($tahun, $bulan, $tanggal);
            $namaHari = strtolower($tanggalCarbon->locale('id')->isoFormat('dddd'));

            if (in_array($namaHari, ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'])) {
                $this->kalender[$tanggal] = $template->{$namaHari};
            }
        }

        $this->kalenderVersion++;
    }

    public function store()
    {
        // Inisialisasi array untuk disimpan ke kolom d1 - d31
        $dataHari = [];
    
        for ($i = 1; $i <= 31; $i++) {
            // Jika user memilih shift untuk tanggal ini, simpan
            if (isset($this->kalender[$i])) {
                $dataHari["d$i"] = $this->kalender[$i]; // nilai bisa nama shift atau id shift
            } else {
                $dataHari["d$i"] = null;
            }
        }
   
        $dataJadwal = array_merge([
            'bulan_tahun' => $this->bulan_tahun,
            'karyawan_id' => $this->selectedKaryawan,
        ], $dataHari);
        // dd($dataJadwal);
        M_Jadwal::Create(
            $dataJadwal
        );
    
        // Reset field
        $this->reset(['bulan_tahun', 'selectedKaryawan', 'kalender', 'selectedTemplateId']);
    
        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);

        $this->dispatch('modalTambahJadwal', action: 'hide');
        $this->dispatch('refresh');
        $this->dispatch('jadwalAdded');
    }

    public function loadData($data)
    {
        $this->jadwal_id = $data['id'];
        $this->bulan_tahun = $data['bulan_tahun'] ?? '';
        $this->selectedKaryawan = $data['karyawan_id'] ?? '';
        // $this->dispatch('$refresh');

        // dd($this->selectedKaryawan);
        $this->kalender = [];
        for ($i = 1; $i <= 31; $i++) {
            $field = 'd' . $i;
            if (isset($data[$field])) {
                $this->kalender[$i] = $data[$field];
            }
        }
        $this->dispatch('$refresh');
    }

    public function loadDetail($data)
    {
        $this->presensiHadir = $data['presensiHadir'] ?? [];
        $this->rekap = $data['rekap'] ?? $this->rekap;
        $this->jadwal_id = $data['id'];
        $this->bulan_tahun = $data['bulan_tahun'] ?? '';
        $this->selectedKaryawan = $data['karyawan_id'] ?? '';
        $this->dispatch('$refresh');

        // dd($this->selectedKaryawan);
        $this->kalender = [];
        for ($i = 1; $i <= 31; $i++) {
            $field = 'd' . $i;
            if (isset($data[$field])) {
                $this->kalender[$i] = $data[$field];
            }
        }
    }

    public function loadRekap($id)
    {
        $presensi = M_Presensi::where('karyawan_id', $id)
            ->whereMonth('tanggal', now()->month)
            ->get();
        $this->rekap['izin'] = $presensi->where('status', 3)->count();
        $this->rekap['cuti'] = $presensi->where('status', 2)->count();
        $this->rekap['terlambat'] = $presensi->where('status', 1)->count();
        $this->rekap['kehadiran'] = $presensi->where('status', '!=', '')->count();
    }

    public function saveEdit()
    {

        $jadwal = M_Jadwal::find($this->jadwal_id);
        // dd($jadwal);
        if (!$jadwal) {
            session()->flash('error', 'Data karyawan tidak ditemukan!');
            return;
        }
        $dataHari = [];
    
        for ($i = 1; $i <= 31; $i++) {
            // Jika user memilih shift untuk tanggal ini, simpan
            if (isset($this->kalender[$i])) {
                $dataHari["d$i"] = $this->kalender[$i]; // nilai bisa nama shift atau id shift
            } else {
                $dataHari["d$i"] = null;
            }
        }
        // update data
        $dataJadwal = array_merge([
            'bulan_tahun' => $this->bulan_tahun,
            'karyawan_id' => $this->selectedKaryawan,
        ], $dataHari);
        // dd($dataJadwal);
        $jadwal->update($dataJadwal);
    
        // Reset field jika mau
        $this->reset(['bulan_tahun', 'selectedKaryawan', 'kalender']);
    
        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        $this->dispatch('modalEditJadwal', action: 'hide');
        $this->dispatch('refresh');
        $this->dispatch('jdawalUpdated');
    }

    public function delete($id)
    {
        // dd($id);
        M_Jadwal::find(Crypt::decrypt($id))->delete();
        $this->dispatch('swal', params: [
            'title' => 'Data Deleted',
            'icon' => 'success',
            'text' => 'Data has been deleted successfully'
        ]);
        $this->dispatch('modal-confirm-delete', action: 'show');
        $this->dispatch('refresh');
    }

    public function mount()
    {
        $this->bulan_tahun = now()->format('Y-m');
        $user = Auth::user();

        $jadwalId = M_Jadwal::where('bulan_tahun', $this->bulan_tahun)
            ->pluck('karyawan_id')
            ->toArray();
        if ($user->hasRole('spv')) {
            $karyawan = M_DataKaryawan::where('user_id', $user->id)->first();
            $divisi = $karyawan->divisi;
            $entitas = $karyawan->entitas;

            $this->karyawans = M_DataKaryawan::where('divisi', $divisi)
                ->where('entitas', $entitas)
                ->whereNotIn('id', $jadwalId) // filter
                ->orderBy('nama_karyawan')
                ->get();
        } elseif ($user->hasRole('admin')) {
            $entitas = session('selected_entitas', 'UHO');

            $this->karyawans = M_DataKaryawan::where('entitas', $entitas)
                ->whereNotIn('id', $jadwalId)
                ->orderBy('nama_karyawan')
                ->get();
        }

        $this->jadwalShifts = M_JadwalShift::orderBy('nama_shift')->get();
        $this->templateWeeks = M_TemplateWeek::orderBy('nama_template')->get();
    }

    public function render()
    {
        $bulan = (int) Carbon::parse($this->bulan_tahun)->format('m');
        $tahun = (int) Carbon::parse($this->bulan_tahun)->format('Y');
        $totalHari = Carbon::create($tahun, $bulan)->daysInMonth;
        $hariPertama = Carbon::create($tahun, $bulan, 1)->dayOfWeek;
        $totalCell = $hariPertama + $totalHari;
        $jumlahBaris = ceil($totalCell / 7);

        return view('livewire.karyawan.modal-jadwal-shift', [
            'jumlahBaris' => $jumlahBaris,
            'hariPertama' => $hariPertama,
            'totalHari' => $totalHari,
        ]);
    }
}
