<?php

namespace App\Livewire\Karyawan\Pengajuan;

use App\Livewire\Forms\LemburForm;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Lembur;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class PengajuanLembur extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';

    public LemburForm $form;
    protected $listeners = ['refreshTable' => 'refresh'];
    public $filterPengajuan = '';
    public $filterBulan = '';
    public $filterKaryawan = '';
    public $karyawanList;
    public $selectedKaryawan = '';
    public $status = [];
    public $search;

    public function mount()
    {
        if (!Auth::check()) {
            session(['redirect_after_login' => url()->current()]);
            return redirect()->to(route('login'));
        }
        // Ambil semua status unik dari database
        $this->status = M_Lembur::select('status')->distinct()->get();
        $this->filterBulan = now()->format('Y-m');

        $entitas = session('selected_entitas', 'UHO');
        $this->karyawanList = M_DataKaryawan::where('entitas', $entitas)
            ->orderBy('nama_karyawan')
            ->get();
    }
    public function showAdd()
    {
        $this->dispatch('modalTambahPengajuanLembur', action: 'show');
    }

    public function showEdit($id)
    {
        $this->form->resetValidation();
        $dataPengajuan = M_Lembur::find(Crypt::decrypt($id));
        // dd($dataPengajuan);
        if (!$dataPengajuan) {
            session()->flash('error', 'Data tiket tidak ditemukan!');
            return;
        }

        // Kirim data ke komponen ModalKunjungan
        $this->dispatch('edit-pengajuan', data: $dataPengajuan->toArray());
        $this->dispatch('modalEditPengajuanLembur', action: 'show');
    }

    public function updateStatus($id, $status = null)
    {
        $pengajuan = M_Lembur::find($id);

        if (!$pengajuan) {
            return;
        }

        $user = Auth::user();
        $entitas = optional($pengajuan->getKaryawan)->entitas;
        $userRoles = $user->getRoleNames()->toArray();
        $pengajuRoles = optional(optional($pengajuan->getKaryawan)->user)
            ?->getRoleNames()
            ->toArray() ?? [];
        $divisi = optional($pengajuan->getKaryawan)->divisi;
        // dd($pengajuRoles);

        // SPV approval
        if (in_array('spv', $userRoles)) {
            if ($status == 1) {
                $pengajuan->approve_spv = 1;
            } elseif ($status == 2) {
                $pengajuan->approve_spv = 2;
                $pengajuan->status = 2;
            }
        }

        // === HR approval ===
        if (in_array('hr', $userRoles)) {
            if ($divisi === 'HR') {
                if ($status == 1) {
                    $pengajuan->approve_hr  = 1;
                    $pengajuan->approve_spv = 1; // auto SPV
                    $pengajuan->status      = 1;
                } elseif ($status == 2) {
                    $pengajuan->approve_hr  = 2;
                    $pengajuan->approve_spv = 2;
                    $pengajuan->status      = 2;

                    $this->dispatch('swal', params: [
                        'title' => 'Pengajuan Rejected',
                        'icon'  => 'error',
                        'text'  => 'Berhasil menolak pengajuan ini.'
                    ]);
                }
            }
            if (in_array('spv', $pengajuRoles)) {
                // Pengaju SPV, HR boleh langsung approve
                if ($status == 1) {
                    $pengajuan->approve_hr = 1;
                    $pengajuan->status = 1;
                } elseif ($status == 2) {
                    $pengajuan->approve_hr = 2;
                    $pengajuan->status = 2;
                    $this->dispatch('swal', params: [
                        'title' => 'Pengajuan Rejected',
                        'icon'  => 'error',
                        'text'  => 'Berhasil Menolak Pengajuan ini.'
                    ]);
                }
            }elseif (in_array('branch-manager', $pengajuRoles)) {
                // hanya HR yang boleh approve
                if ($status == 1) {
                    $pengajuan->approve_hr = 1;
                    $pengajuan->status     = 1;
                } elseif ($status == 2) {
                    $pengajuan->approve_hr = 2;
                    $pengajuan->status     = 2;

                    $this->dispatch('swal', params: [
                        'title' => 'Pengajuan Ditolak',
                        'icon'  => 'error',
                        'text'  => 'Berhasil menolak pengajuan branch-manager.'
                    ]);
                }
            } else {
                if (in_array($entitas, ['MC', 'FC'])){
                    if ($status == 1) {
                        $pengajuan->approve_hr = 1;
                        $pengajuan->status = 1;
                    } elseif ($status == 2) {
                        $pengajuan->approve_hr = 2;
                        $pengajuan->status = 2;
                    }
                } else {
                    // Jika pengaju bukan SPV, maka perlu approve_spv
                    if ($pengajuan->approve_spv == 1) {
                        if ($status == 1) {
                            $pengajuan->approve_hr = 1;
                            $pengajuan->status = 1;
                        } elseif ($status == 2) {
                            $pengajuan->approve_hr = 2;
                            $pengajuan->status = 2;

                            $this->dispatch('swal', params: [
                                'title' => 'Pengajuan Rejected',
                                'icon' => 'error',
                                'text' => 'Berhasil Menolak Pengajuan ini.'
                            ]);
                        }
                    } elseif ($pengajuan->approve_spv == 2) {
                        $pengajuan->status = 2;
                        $this->dispatch('swal', params: [
                            'title' => 'Gagal Menyimpan',
                            'icon' => 'error',
                            'text' => 'SPV sudah menolak pengajuan ini.'
                        ]);
                        return;
                    } else {
                        $this->dispatch('swal', params: [
                            'title' => 'Gagal Menyimpan',
                            'icon' => 'error',
                            'text' => 'Pengajuan belum disetujui oleh SPV.'
                        ]);
                        return;
                    }
                }
            }
        }

        // === Admin approval ===
        if (in_array('admin', $userRoles)) {
            if (!array_intersect(['hr', 'admin'], $pengajuRoles)) {
                $this->dispatch('swal', params: [
                    'title' => 'Tidak Diizinkan',
                    'icon'  => 'error',
                    'text'  => 'Admin hanya bisa menyetujui pengajuan dari HR.'
                ]);
                return;
            }

            if ($status == 1) {
                $pengajuan->approve_admin = 1;
                $pengajuan->status = 1;
            } elseif ($status == 2) {
                $pengajuan->approve_admin = 2;
                $pengajuan->status = 2;
            }
        }

        // Final status kalau SPV & HR sudah approve
        if ($pengajuan->approve_spv == 1 && $pengajuan->approve_hr == 1) {
            $pengajuan->status = 1;
        }

        $pengajuan->save();

        $this->dispatch('swal', params: [
            'title' => 'Status Diperbarui',
            'icon' => 'success',
            'text' => 'Status dan jadwal berhasil diperbarui.'
        ]);

        $this->dispatch('refresh');
    }

    public function render()
    {
        $query = M_Lembur::with('getKaryawan');
        $user = Auth::user();

        // Ambil nama entitas dari session
        $entitas = session('selected_entitas', 'UHO');

        // 🔹 User biasa → hanya lemburnya sendiri
        if ($user->hasRole('user|branch-manager')) {
            $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();
            if ($dataKaryawan) {
                $query->where('karyawan_id', $dataKaryawan->id);
            }

        // 🔹 Admin → semua karyawan dalam entitas terpilih
        } elseif ($user->hasRole('admin')) {
            $entitasModel = \App\Models\M_Entitas::where('nama', $entitas)->first();
            if ($entitasModel) {
                $karyawanIdList = M_DataKaryawan::where('entitas', $entitas)->pluck('id');
                $query->whereIn('karyawan_id', $karyawanIdList);
            }

        // 🔹 SPV → hanya karyawan dengan divisi + entitas sama
        } elseif ($user->hasRole('spv')) {
            $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();
            if ($dataKaryawan && $dataKaryawan->divisi) {
                if (strtolower($dataKaryawan->divisi) === 'noc') {
                    // Divisi NOC → tanpa filter entitas
                    $karyawanIdList = M_DataKaryawan::where('divisi', $dataKaryawan->divisi)
                        ->pluck('id');
                } elseif ($dataKaryawan->entitas) {
                    // Divisi lain → filter divisi + entitas
                    $karyawanIdList = M_DataKaryawan::where('divisi', $dataKaryawan->divisi)
                        ->where('entitas', $dataKaryawan->entitas)
                        ->pluck('id');
                } else {
                    $karyawanIdList = collect();
                }
                $query->whereIn('karyawan_id', $karyawanIdList);
            }

        // 🔹 HR → semua karyawan dari semua entitas
        } elseif ($user->hasRole('hr')) {
            $entitasModel = \App\Models\M_Entitas::where('nama', $entitas)->first();
            if ($entitasModel) {
                $karyawanIdList = M_DataKaryawan::where('entitas', $entitas)->pluck('id');
                $query->whereIn('karyawan_id', $karyawanIdList);
            }
        }

        // 🔹 Filter status pengajuan
        if (in_array($this->filterPengajuan, ['0', '1', '2'], true)) {
            $query->where('status', (int) $this->filterPengajuan);
        }

        // 🔹 Filter bulan
        if (!empty($this->filterBulan)) {
            $bulan = date('m', strtotime($this->filterBulan));
            $tahun = date('Y', strtotime($this->filterBulan));
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }

        // 🔹 Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('tanggal', 'like', '%' . $this->search . '%')
                    ->orWhere('id', 'like', '%' . $this->search . '%');
            });
        }

        $pengajuanLembur = $query->latest()->paginate(10);

        return view('livewire.karyawan.pengajuan.pengajuan-lembur', [
            'pengajuanLembur' => $pengajuanLembur,
        ]);
    }


}
