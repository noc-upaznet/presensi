<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\M_Presensi;
use Livewire\WithPagination;
use App\Models\M_DataKaryawan;
use App\Models\User;
use App\Traits\CutoffPayrollTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\WithoutUrlPagination;

class RiwayatPresensiStaff extends Component
{
    use WithPagination, WithoutUrlPagination;
    use CutoffPayrollTrait;
    protected $paginationTheme = 'bootstrap';
    public $karyawanList;
    public $filterTanggal;
    public $filterBulan;
    public $filterkaryawan;
    public $filterStatus;
    public $editId;
    public $status;
    public $perPage = 25;

    public $statusList = [
        0 => 'Tepat Waktu',
        1 => 'Terlambat',
        2 => 'Dispensasi',
    ];

    public function mount()
    {
        $today = Carbon::today();

        $year  = $today->year;
        $month = $today->month;

        if ($today->day >= 26) {
            $month++;
            if ($month > 12) {
                $month = 1;
                $year++;
            }
        }

        $this->filterBulan = sprintf('%04d-%02d', $year, $month);
        $user = Auth::user();
        $userId = $user->id;
        $currentRole = $user->current_role;

        $karyawan = M_DataKaryawan::where('user_id', $userId)->first();
        $entitasNama = $karyawan->entitas ?? null;

        // ambil dari session kalau ada
        $entitasNama = session('selected_entitas', $entitasNama ?? 'UHO');

        $divisi = $karyawan ? $karyawan->divisi : null;

        if ($user->hasRole('hr')) {
            // HR bisa pilih entitas via session
            $selectedEntitas = session('selected_entitas', 'all');

            if ($selectedEntitas === 'all') {
                $this->karyawanList = M_DataKaryawan::select('id', 'nama_karyawan')->get();
            } else {
                $this->karyawanList = M_DataKaryawan::where('entitas', $selectedEntitas)
                    ->select('id', 'nama_karyawan')
                    ->get();
            }
        } elseif ($divisi === 'NOC') {
            $this->karyawanList = M_DataKaryawan::where('divisi', 'NOC')
                ->select('id', 'nama_karyawan')
                ->get();
        } elseif ($user->hasRole('branch-manager')) {
            $this->karyawanList = M_DataKaryawan::where('entitas', $entitasNama)
                // ->where('divisi', $divisi)
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

        if (!$presensi) {
            session()->flash('error', 'Data presensi tidak ditemukan!');
            return;
        }

        $user = Auth::user();

        // cari level karyawan yang presensi
        $karyawan = M_DataKaryawan::where('id', $presensi->user_id)->first();

        // === simpan status lama sebelum diubah ===
        $presensi->previous_status = $presensi->status;

        // === proses approval ===
        if ($user->hasRole('spv')) {
            $presensi->approve_late_spv = 1;
        } elseif ($user->hasRole('hr')) {
            $presensi->approve_late_hr = 1;
        }

        // === logika perubahan status ===
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
            'text'  => "Status berubah dari {$presensi->previous_status} ke {$presensi->status}"
        ]);
    }

    public function showModal($id)
    {
        $decryptedId = Crypt::decrypt($id);
        $this->editId = $decryptedId;

        $data = M_Presensi::find($decryptedId);
        // dd($data);
        if (!$data) {
            session()->flash('error', 'Data tiket tidak ditemukan!');
            return;
        }

        $this->status = $data->status;
        $this->dispatch('editModal', action: 'show');
    }

    public function updateStatus()
    {
        // Cari data berdasarkan ID
        $data = M_Presensi::find($this->editId);

        if (!$data) {
            session()->flash('error', 'Data presensi tidak ditemukan!');
            return;
        }

        // Simpan status lama sebelum diganti
        $data->previous_status = $data->status;

        // Update field status dengan nilai baru dari form
        $data->status = $this->status;
        $data->save();

        // Opsional: tampilkan notifikasi
        $this->dispatch('swal', params: [
            'title' => 'Berhasil',
            'text'  => "Status berubah dari {$data->previous_status} ke {$data->status}",
            'icon'  => 'success'
        ]);

        // Reset form
        $this->reset(['editId', 'status']);

        // Tutup modal
        $this->dispatch('editModal', action: 'hide');
    }


    public function render()
    {
        $user   = Auth::user();
        $userId = $user->id;

        $karyawan = M_DataKaryawan::where('user_id', $userId)->first();

        $karyawanId = $karyawan->id;
        $divisi     = $karyawan->divisi;
        $entitas    = $karyawan->entitas;

        // =========================================
        // Resolve Tahun & Bulan (untuk cutoff)
        // =========================================
        if (!empty($this->filterBulan)) {
            [$year, $month] = explode('-', $this->filterBulan);
        } else {
            $year  = now()->year;
            $month = now()->month;
        }

        $cutoff      = $this->resolveCutoff($year, $month, 'cutoff_25');
        $cutoffStart = $cutoff['start'];
        $cutoffEnd   = $cutoff['end'];

        $query = M_Presensi::with('getUser')
            ->where('user_id', '!=', $karyawanId)
            ->whereBetween('tanggal', [
                $cutoffStart,
                $cutoffEnd
            ])

            ->when($this->filterTanggal, function ($q) {
                $q->whereDate('created_at', $this->filterTanggal);
            })

            ->when($this->filterkaryawan, function ($q) {
                $q->where('user_id', $this->filterkaryawan);
            })

            ->when($this->filterStatus !== null, function ($q) {
                $q->where('status', $this->filterStatus);
            });

        // =========================================
        // ROLE FILTER
        // =========================================
        if ($user->hasRole('spv')) {

            if ($divisi === 'NOC') {
                $query->whereHas('getUser', function ($q) use ($divisi) {
                    $q->where('divisi', $divisi);
                });
            } else {
                $query->whereHas('getUser', function ($q) use ($divisi, $entitas) {
                    $q->where('divisi', $divisi)
                        ->where('entitas', $entitas);
                });
            }
        } elseif ($user->hasRole('hr')) {

            $entitasSession = session('selected_entitas', 'UHO');

            $query->whereHas('getUser', function ($q) use ($entitasSession) {
                $q->where('entitas', $entitasSession);
            });
        } elseif ($user->hasRole('branch-manager')) {

            $query->whereHas('getUser', function ($q) {
                $q->where('entitas', 'UNB');
            });
        }

        $datas = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.riwayat-presensi-staff', [
            'datas' => $datas
        ]);
    }
}
