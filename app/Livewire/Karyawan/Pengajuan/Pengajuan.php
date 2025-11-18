<?php

namespace App\Livewire\Karyawan\Pengajuan;

use App\Livewire\Forms\PengajuanForm;
use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Pengajuan;
use Livewire\WithPagination;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\WithoutUrlPagination;

class Pengajuan extends Component
{
    public PengajuanForm $form;
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['refreshTable' => 'refresh'];

    public $filterPengajuan = '';
    public $filterBulan = '';
    public $filterKaryawan = '';
    public $karyawanList;
    public $selectedKaryawan = '';
    public $status = [];
    public $search;
    public $tanggal;
    public $keterangan;

    public function mount()
    {
        if (!Auth::check()) {
            session(['redirect_after_login' => url()->current()]);
            return redirect()->to(route('login'));
        }
        // Ambil semua status unik dari database
        $this->status = M_Pengajuan::select('status')->distinct()->get();
        $this->filterBulan = now()->format('Y-m');
        $entitas = session('selected_entitas', 'UHO');
        $this->karyawanList = M_DataKaryawan::where('entitas', $entitas)
            ->orderBy('nama_karyawan')
            ->get();
    }

    public function showAdd()
    {
        // Dispatch event ke modal
        $this->dispatch('modalTambahPengajuan', action: 'show');
    }

    public function showEdit($id)
    {
        $this->form->resetValidation();
        $dataPengajuan = M_Pengajuan::find(Crypt::decrypt($id));
        // dd($dataPengajuan);
        if (!$dataPengajuan) {
            session()->flash('error', 'Data tiket tidak ditemukan!');
            return;
        }

        // Kirim data ke komponen ModalKunjungan
        $this->dispatch('edit-pengajuan', data: $dataPengajuan->toArray());
        $this->dispatch('modalEditPengajuan', action: 'show');
    }

    public function updateStatus($id, $status = null)
    {
        $pengajuan = M_Pengajuan::find($id);

        if (!$pengajuan) {
            return;
        }

        $user = Auth::user();
        $userRoles = $user->getRoleNames()->toArray();
        $pengajuRoles = optional(optional($pengajuan->getKaryawan)->user)
            ?->getRoleNames()
            ->toArray() ?? [];
        $entitasUser = optional($pengajuan->getKaryawan)->entitas;
        $divisi = optional($pengajuan->getKaryawan)->divisi;
        // === SPV approval ===
        if (in_array('spv', $userRoles)) {
            if ($status == 1) {
                $pengajuan->approve_spv = 1;
            } elseif ($status == 2) {
                $pengajuan->approve_spv = 2;
                $pengajuan->status = 2;
            }
        }

        if (in_array('branch-manager', $userRoles)) {
            if ($status == 1) {
                $pengajuan->approve_spv = 1;
            } elseif ($status == 2) {
                $pengajuan->approve_spv = 2;
                $pengajuan->status = 2;
            }
        }

        // === HR approval ===
        if ($user->hasRole('hr')) {

            // Jika pengaju dari divisi HR → langsung approve HR & SPV
            if ($divisi === 'HR') {
                if ($status == 1) {
                    $pengajuan->approve_hr  = 1;
                    $pengajuan->approve_spv = 1;
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
            // Jika pengaju adalah SPV → HR boleh langsung approve
            elseif (in_array('spv', $pengajuRoles)) {
                if ($status == 1) {
                    $pengajuan->approve_hr = 1;
                    $pengajuan->status     = 1;
                } elseif ($status == 2) {
                    $pengajuan->approve_hr = 2;
                    $pengajuan->status     = 2;
                    $this->dispatch('swal', params: [
                        'title' => 'Pengajuan Rejected',
                        'icon'  => 'error',
                        'text'  => 'Berhasil Menolak Pengajuan ini.'
                    ]);
                }
            } elseif (in_array('branch-manager', $pengajuRoles)) {
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
            }
            // Jika bukan kasus di atas → ikuti flow SPV dulu
            else {
                if (in_array($entitasUser, ['MC'])) {
                    if ($status == 1) {
                        $pengajuan->approve_hr = 1;
                        $pengajuan->status     = 1;
                    } elseif ($status == 2) {
                        $pengajuan->approve_hr = 2;
                        $pengajuan->status     = 2;
                    }
                } else {
                    if ($pengajuan->approve_spv == 1) {
                        if ($status == 1) {
                            $pengajuan->approve_hr = 1;
                            $pengajuan->status     = 1;
                        } elseif ($status == 2) {
                            $pengajuan->approve_hr = 2;
                            $pengajuan->status     = 2;
                            $this->dispatch('swal', params: [
                                'title' => 'Pengajuan Rejected',
                                'icon'  => 'error',
                                'text'  => 'Berhasil Menolak Pengajuan ini.'
                            ]);
                        }
                    } elseif ($pengajuan->approve_spv == 2) {
                        $pengajuan->status = 2;
                        $this->dispatch('swal', params: [
                            'title' => 'Gagal Menyimpan',
                            'icon'  => 'error',
                            'text'  => 'SPV sudah menolak pengajuan ini.'
                        ]);
                        return;
                    } else {
                        $this->dispatch('swal', params: [
                            'title' => 'Gagal Menyimpan',
                            'icon'  => 'error',
                            'text'  => 'Pengajuan belum disetujui oleh SPV.'
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

        // === Update jadwal kalau status = 1 ===
        if ($pengajuan->status == 1) {
            $tanggal    = Carbon::parse($pengajuan->tanggal);
            $hari       = 'd' . $tanggal->day;
            $bulanTahun = $tanggal->format('Y-m');

            $jadwal = M_Jadwal::where('karyawan_id', $pengajuan->karyawan_id)
                ->where('bulan_tahun', $bulanTahun)
                ->first();

            if ($jadwal) {
                $pengajuan->jadwal_sebelumnya = $jadwal->$hari;
                $pengajuan->save();

                $jadwal->$hari = $pengajuan->shift_id;
                $jadwal->save();
            } else {
                $pengajuan->jadwal_sebelumnya = null;
                $pengajuan->save();

                $jadwalBaru = new M_Jadwal([
                    'karyawan_id' => $pengajuan->karyawan_id,
                    'bulan_tahun' => $bulanTahun,
                    $hari         => $pengajuan->shift_id,
                ]);
                $jadwalBaru->save();
            }
        }

        $this->dispatch('swal', params: [
            'title' => 'Status Diperbarui',
            'icon'  => 'success',
            'text'  => 'Status dan jadwal berhasil diperbarui.'
        ]);

        $this->dispatch('refresh');
    }

    public function render()
    {
        $query = M_Pengajuan::with(['getKaryawan', 'getShift']);
        $user = Auth::user();
        $entitas = session('selected_entitas', 'UHO');

        if ($user->hasRole('user')) {
            $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();
            if ($dataKaryawan) {
                $query->where('karyawan_id', $dataKaryawan->id);
            }
        } elseif ($user->hasRole('admin')) {
            $karyawanIdList = M_DataKaryawan::where('entitas', $entitas)->pluck('id');
            $query->whereIn('karyawan_id', $karyawanIdList);
        } elseif ($user->hasRole('spv')) {
            $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();
            if ($dataKaryawan) {
                if (strtolower($dataKaryawan->divisi) === 'noc') {
                    // Divisi NOC → tidak pakai entitas
                    $karyawanIdList = M_DataKaryawan::where('divisi', $dataKaryawan->divisi)
                        ->pluck('id');
                } else {
                    // Divisi lain → filter divisi + entitas
                    $karyawanIdList = M_DataKaryawan::where('divisi', $dataKaryawan->divisi)
                        ->where('entitas', $dataKaryawan->entitas)
                        ->pluck('id');
                }
                $query->whereIn('karyawan_id', $karyawanIdList);
            }

            // 🔹 HR → semua karyawan semua entitas
        } elseif ($user->hasRole('hr')) {
            $karyawanIdList = M_DataKaryawan::where('entitas', $entitas)->pluck('id');
            $query->whereIn('karyawan_id', $karyawanIdList);
        } elseif ($user->hasRole('branch-manager')) {
            $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();
            $karyawanIdList = M_DataKaryawan::where('entitas', $dataKaryawan->entitas)
                ->pluck('id');
            $query->whereIn('karyawan_id', $karyawanIdList);
            // dd($entitas);
        }

        $query->when($this->selectedKaryawan, function ($query) {
            $query->where('karyawan_id', $this->selectedKaryawan);
        });

        // 🔹 Filter Status
        if (in_array($this->filterPengajuan, ['0', '1', '2'], true)) {
            $query->where('status', (int) $this->filterPengajuan);
        }

        // 🔹 Filter Bulan
        if (!empty($this->filterBulan)) {
            $bulan = date('m', strtotime($this->filterBulan));
            $tahun = date('Y', strtotime($this->filterBulan));
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }

        // 🔹 Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                    ->orWhere('tanggal', 'like', '%' . $this->search . '%');
            });
        }

        $pengajuan = $query->orderBy('tanggal', 'desc')->paginate(10);

        return view('livewire.karyawan.pengajuan.pengajuan', [
            'pengajuans' => $pengajuan,
        ]);
    }
}
