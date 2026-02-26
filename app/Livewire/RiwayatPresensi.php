<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\M_Presensi;
use Livewire\WithPagination;
use App\Models\M_DataKaryawan;
use App\Traits\CutoffPayrollTrait;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class RiwayatPresensi extends Component
{
    use WithPagination, WithoutUrlPagination;
    use CutoffPayrollTrait;

    protected $paginationTheme = 'bootstrap';

    public $editId;
    public $statusList = [
        0 => 'Tepat Waktu',
        1 => 'Terlambat',
        2 => 'Dispensasi',
    ];
    public $status;
    public $filterTanggal;
    public $filterBulan;
    public $filterkaryawan;
    public $filterStatus;
    public $karyawanList;
    public $perPage = 25;

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
        // $this->statusList = M_Presensi::select('status')->distinct()->pluck('status')->toArray();
        $entitasNama = session('selected_entitas', 'UHO');
        $this->karyawanList = M_DataKaryawan::where('entitas', $entitasNama)
            ->select('id', 'nama_karyawan')
            ->get();
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

    public function delete($id)
    {
        M_Presensi::find(Crypt::decrypt($id))->delete();
        $this->dispatch('modal-confirm-delete', action: 'hide');
        $this->dispatch('swal', params: [
            'title' => 'Presensi deleted',
            'text' => 'Presensi deleted successfully',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $user        = Auth::user();
        $entitasNama = session('selected_entitas', 'UHO');

        // =========================
        // Resolve Tahun & Bulan
        // =========================
        if (!empty($this->filterBulan)) {
            [$year, $month] = explode('-', $this->filterBulan);
        } else {
            $year  = now()->year;
            $month = now()->month;
        }

        // =========================
        // Resolve Cutoff 26–25
        // =========================
        $cutoff      = $this->resolveCutoff($year, $month, 'cutoff_25');
        $cutoffStart = $cutoff['start'];
        $cutoffEnd   = $cutoff['end'];

        $query = M_Presensi::with('getUser')
            ->whereBetween('tanggal', [
                $cutoffStart->toDateTimeString(),
                $cutoffEnd->toDateTimeString(),
            ]);

        $query->when($this->filterTanggal, function ($q) {
            $q->whereDate('created_at', $this->filterTanggal);
        });

        if ($user->hasRole('admin')) {

            $query->whereHas('getUser', function ($q) use ($entitasNama) {
                $q->where('entitas', $entitasNama);
            });

            $query->when($this->filterkaryawan, function ($q) {
                $q->where('user_id', $this->filterkaryawan);
            });

            $query->when($this->filterStatus !== null, function ($q) {
                $q->where('status', $this->filterStatus);
            });

            // Khusus UNB
            if ($entitasNama === 'UNB') {
                $query->where(function ($q) {
                    $q->whereHas('getKaryawan', function ($sub) {
                        $sub->whereNotIn('level', ['SPV', 'Manajer', 'Branch Manager']);
                    })
                        ->where(function ($q2) {
                            $q2->where(function ($q3) {
                                $q3->where('lokasi_lock', 0)
                                    ->where('approve', 1);
                            })
                                ->orWhere(function ($q3) {
                                    $q3->where('lokasi_lock', 1)
                                        ->where('approve', 0);
                                });
                        })
                        ->orWhereHas('getKaryawan', function ($sub) {
                            $sub->whereIn('level', ['SPV', 'Manajer', 'Branch Manager']);
                        });
                });
            }
        } elseif ($user->hasAnyRole(['user', 'spv', 'hr', 'branch-manager'])) {

            $karyawanId = optional(
                M_DataKaryawan::where('user_id', $user->id)->first()
            )->id;

            $query->where('user_id', $karyawanId);
        }

        $datas = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.riwayat-presensi', [
            'datas' => $datas
        ]);
    }
}
