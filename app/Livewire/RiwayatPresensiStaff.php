<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\M_Presensi;
use Livewire\WithPagination;
use App\Models\M_DataKaryawan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class RiwayatPresensiStaff extends Component
{
    use WithPagination;
    public $karyawanList;
    public $filterTanggal;
    public $filterBulan;
    public $filterkaryawan;

    public function mount()
    {
        $this->filterBulan = Carbon::now()->format('Y-m');
        $userId = Auth::id();
        $karyawan = M_DataKaryawan::where('user_id', $userId)->first();
        $entitasNama = $karyawan->entitas;
        // dd($entitasNama);

        $divisi = $karyawan ? $karyawan->divisi : null;
        // dd($divisi);
        if ($divisi === 'NOC') {
            $this->karyawanList = M_DataKaryawan::where('divisi', 'NOC')
            ->select('id', 'nama_karyawan')
            ->get();
        } else {
            $this->karyawanList = M_DataKaryawan::where('entitas', $entitasNama)
            ->where('divisi', $divisi)
            ->select('id', 'nama_karyawan')
            ->get();
        }
    }
    public function approve($id)
    {
        $presensi = M_Presensi::findOrFail($id);
        $presensi->approve = '1';
        $presensi->save();

        $this->dispatch('swal', params: [
            'title' => 'Presensi Approved',
            'icon' => 'success',
            'text' => 'Presensi has been approved successfully'
        ]);
    }

    public function reject($id)
    {
        $presensi = M_Presensi::findOrFail($id);
        $presensi->approve = '2';
        $presensi->save();

        $this->dispatch('swal', params: [
            'title' => 'Presensi Rejected',
            'icon' => 'error',
            'text' => 'Presensi has been rejected successfully'
        ]);
    }

    public function approvePresensi($id)
    {
        $presensi = M_Presensi::find($id);

        if ($presensi) {
            $role = Auth::user()->current_role;

            // update sesuai role
            if ($role === 'spv') {
                $presensi->approve_late_spv = 1;
            } elseif ($role === 'hr') {
                $presensi->approve_late_hr = 1;
            }

            // jika keduanya sudah approve dan status = 1, update status jadi 2
            if ($presensi->status == 1 
                && $presensi->approve_late_spv == 1 
                && $presensi->approve_late_hr == 1) {
                $presensi->status = 2;
            }

            $presensi->save();

            $this->dispatch('swal', params: [
                'title' => 'Presensi Approved ✅',
                'icon'  => 'success',
                'text'  => 'Approval berhasil disimpan'
            ]);
        }
    }


    public function render()
    {
        $userId = Auth::id();
        // Ambil data karyawan dari user yang login
        $karyawan = M_DataKaryawan::where('user_id', $userId)->first();
        // dd($karyawan);
        $currentRole = Auth::user()->current_role;

        $divisi = $karyawan->divisi;
        // dd($divisi);
        $karyawanId = $karyawan->id;

        $entitasNama = $karyawan->entitas;
        // dd($entitasNama);
        if($currentRole === 'spv')
        {
            $datas = M_Presensi::with('getUser')
            // ->where('lokasi_lock', 0)
            ->when($this->filterTanggal, function ($query) {
                    $query->whereDate('created_at', $this->filterTanggal);
                }, function ($query) {
                    if ($this->filterBulan) {
                        [$year, $month] = explode('-', $this->filterBulan);
                        $query->whereYear('created_at', $year)
                            ->whereMonth('created_at', $month);
                    } else {
                        $query->whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year);
                    }
                })
            ->when($this->filterkaryawan, function ($query) {
                    $query->where('user_id', $this->filterkaryawan);
                })
            ->where('user_id', '!=', $karyawanId)
            ->whereHas('getUser', function ($query) use ($divisi, $entitasNama) {
                $query->where('divisi', $divisi)
                    ->where('entitas', $entitasNama);
            })
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }elseif($currentRole === 'hr'){
            $datas = M_Presensi::with('getUser')
                // ->where('lokasi_lock', 0)
                ->when($this->filterTanggal, function ($query) {
                    $query->whereDate('created_at', $this->filterTanggal);
                }, function ($query) {
                    if ($this->filterBulan) {
                        [$year, $month] = explode('-', $this->filterBulan);
                        $query->whereYear('created_at', $year)
                            ->whereMonth('created_at', $month);
                    } else {
                        $query->whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year);
                    }
                })
                ->when($this->filterkaryawan, function ($query) {
                    $query->where('user_id', $this->filterkaryawan);
                })
                ->where('user_id', '!=', $karyawanId)
                ->whereHas('getUser', function ($q) {
                    $q->where('divisi', 'Teknisi');
                })
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        

        return view('livewire.riwayat-presensi-staff', [
            'datas' => $datas
        ]);
    }



}
