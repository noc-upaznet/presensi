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
    protected $paginationTheme = 'bootstrap';

    public $karyawanList;
    public $filterTanggal;
    public $filterBulan;
    public $filterkaryawan;
    public $filterStatus = '';

    public function mount()
    {
        $this->filterBulan = Carbon::now()->format('Y-m');
        $user = Auth::user();
        $userId = $user->id;
        $currentRole = $user->current_role;

        $karyawan = M_DataKaryawan::where('user_id', $userId)->first();
        $entitasNama = $karyawan->entitas ?? null;

        // ambil dari session kalau ada
        $entitasNama = session('selected_entitas', $entitasNama ?? 'UHO');

        $divisi = $karyawan ? $karyawan->divisi : null;

        if ($currentRole === 'hr') {
            // HR bisa pilih entitas via session
            $selectedEntitas = session('selected_entitas', 'all');

            if ($selectedEntitas === 'all') {
                $this->karyawanList = M_DataKaryawan::select('id', 'nama_karyawan')->get();
            } else {
                $this->karyawanList = M_DataKaryawan::where('entitas', $selectedEntitas)
                    ->select('id', 'nama_karyawan')
                    ->get();
            }
        }
        elseif ($divisi === 'NOC') {
            $this->karyawanList = M_DataKaryawan::where('divisi', 'NOC')
                ->select('id', 'nama_karyawan')
                ->get();
        }
        else {
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

            // cari level karyawan yang presensi
            $karyawan = M_DataKaryawan::where('id', $presensi->user_id)->first();

            if ($role === 'spv') {
                $presensi->approve_late_spv = 1;
            } elseif ($role === 'hr') {
                $presensi->approve_late_hr = 1;
            }

            // === logika status ===
            if ($presensi->status == 1) {
                if ($karyawan && $karyawan->level === 'SPV') {
                    // kalau karyawan adalah SPV -> hanya butuh approve HR
                    if ($presensi->approve_late_hr == 1) {
                        $presensi->status = 2;
                    }
                } else {
                    // kalau bukan SPV -> butuh approve SPV + HR
                    if ($presensi->approve_late_spv == 1 && $presensi->approve_late_hr == 1) {
                        $presensi->status = 2;
                    }
                }
            }

            $presensi->save();

            $this->dispatch('swal', params: [
                'title' => 'Presensi Approved',
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
            if ($divisi === 'NOC') {
                $datas = M_Presensi::with('getUser')
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
                    ->when($this->filterStatus !== null, function ($query) {
                        // Filter berdasarkan status presensi
                        if ($this->filterStatus == 0) {
                            $query->where('status', 0); // Tepat Waktu
                        } elseif ($this->filterStatus == 1) {
                            $query->where('status', 1); // Terlambat
                        } elseif ($this->filterStatus == 2) {
                            $query->where('status', 2); // Dispensasi
                        }
                    })
                    ->where('user_id', '!=', $karyawanId)
                    ->whereHas('getUser', function ($query) use ($divisi) {
                        $query->where('divisi', $divisi);
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            } else {
                $datas = M_Presensi::with('getUser')
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
                ->when($this->filterStatus !== null, function ($query) {
                    // Filter berdasarkan status presensi
                    if ($this->filterStatus == 0) {
                        $query->where('status', 0); // Tepat Waktu
                    } elseif ($this->filterStatus == 1) {
                        $query->where('status', 1); // Terlambat
                    } elseif ($this->filterStatus == 2) {
                        $query->where('status', 2); // Dispensasi
                    }
                })
                ->where('user_id', '!=', $karyawanId)
                ->whereHas('getUser', function ($query) use ($divisi, $entitasNama) {
                    $query->where('divisi', $divisi)
                        ->where('entitas', $entitasNama);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            }
        }elseif ($currentRole === 'hr') {
            $entitasNama = session('selected_entitas', 'UHO');

            $datas = M_Presensi::with('getUser')
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
                ->when($this->filterStatus !== null, function ($query) {
                    // Filter berdasarkan status presensi
                    if ($this->filterStatus == 0) {
                        $query->where('status', 0); // Tepat Waktu
                    } elseif ($this->filterStatus == 1) {
                        $query->where('status', 1); // Terlambat
                    } elseif ($this->filterStatus == 2) {
                        $query->where('status', 2); // Dispensasi
                    }
                })
                ->where('user_id', '!=', $karyawanId)
                ->whereHas('getUser', function ($q) use ($entitasNama) {
                    $q->where('divisi', 'Teknisi')
                    ->where('entitas', $entitasNama); // filter entitas dari session
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        

        return view('livewire.riwayat-presensi-staff', [
            'datas' => $datas
        ]);
    }



}
