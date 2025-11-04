<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\M_Presensi;
use Livewire\WithPagination;
use App\Models\M_DataKaryawan;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class RiwayatPresensi extends Component
{
    use WithPagination, WithoutUrlPagination;
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

    public function mount()
    {
        $this->filterBulan = Carbon::now()->format('Y-m');
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
        $entitasNama = session('selected_entitas', 'UHO');

        if (Auth::user()->hasRole(['admin'])) {
            $datas = M_Presensi::with('getUser')
                ->when($this->filterTanggal, function ($query) {
                    $query->whereDate('created_at', $this->filterTanggal);
                }, function ($query) {
                    if ($this->filterBulan) {
                        [$year, $month] = explode('-', $this->filterBulan);
                        $query->whereYear('created_at', $year)
                            ->whereMonth('created_at', $month);
                    } else {
                        $query->whereDate('created_at', now()->toDateString());
                    }
                })
                ->when($this->filterkaryawan, function ($query) {
                    $query->where('user_id', $this->filterkaryawan);
                })
                ->whereHas('getUser', function ($query) use ($entitasNama) {
                    $query->where('entitas', $entitasNama);
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
                ->when($entitasNama === 'UNB', function ($query) {
                    // Hanya terapkan filter approve untuk entitas selain UNB
                    $query->where(function ($q) {
                        $q->whereHas('getKaryawan', function ($sub) {
                            $sub->whereNotIn('level', ['SPV', 'Manajer, Branch Manager']);
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
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } elseif (Auth::user()->hasAnyRole(['user', 'spv', 'hr', 'branch-manager'])) {
            $userId = Auth::id();
            $karyawan = M_DataKaryawan::where('user_id', $userId)->first();
            $karyawanId = $karyawan ? $karyawan->id : null;

            $datas = M_Presensi::where('user_id', $karyawanId)
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
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // fallback supaya tidak error
            $datas = collect();
        }

        return view('livewire.riwayat-presensi', [
            'datas' => $datas
        ]);
    }
}
