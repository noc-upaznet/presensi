<?php

namespace App\Livewire\Karyawan\Pengajuan;

use App\Livewire\Forms\DispensasiForm;
use App\Models\M_DataKaryawan;
use App\Models\M_Dispensation;
use App\Models\M_Presensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Dispensasi extends Component
{
    use WithFileUploads;
    public DispensasiForm $form;
    public $file;
    public $filterPengajuan;
    public $search;

    public function showAdd()
    {
        $this->dispatch('modalTambahPengajuan', action: 'show');
    }

    public function store()
    {
        $this->form->validate();

        if ($this->file) {
            // dd($this->file);
            $this->validate([
                'file' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            ], [
                'file.max' => 'Ukuran file maksimal 2MB.',
                'file.mimes' => 'Format file harus JPG, JPEG, PNG.',
            ]);
        }

        $path = null;
        if ($this->file) {
            $path = $this->file->store('file-pengajuan-dispensasi', 'public');
        }
        // dd(auth()->id());
        $data = [
            'karyawan_id' => M_DataKaryawan::where('user_id', Auth::id())->value('id'),
            'date' => $this->form->date,
            'description' => $this->form->description,
            'file' => $path ? str_replace('public/', 'storage/', $path) : null,
        ];
        // dd($data);

        // Simpan data ke database
        M_Dispensation::create($data);

        // Reset input
        $this->form->reset();

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);

        // Tutup modal
        $this->dispatch('modalTambahPengajuan', action: 'hide');
        $this->dispatch('refresh');
    }

    public function updateStatus($id, $status = null)
    {
        $pengajuan = M_Dispensation::find($id);

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
            }
            // Jika user login juga punya role branch-manager → boleh langsung approve HR
            elseif (in_array('branch-manager', $pengajuRoles)) {
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
                if ($entitasUser === 'MC') {
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

        if ($pengajuan->status == 1) {
            M_Presensi::where('user_id', $pengajuan->karyawan_id)
                ->whereDate('tanggal', $pengajuan->date)
                ->update([
                    'previous_status' => DB::raw('status'),
                    'status'          => 2
                ]);
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
        $query = M_Dispensation::with(['getKaryawan']);
        $user = Auth::user();
        $entitas = session('selected_entitas', 'UHO'); // default ke 'UHO'

        // 🔹 User biasa → hanya lihat datanya sendiri
        if ($user->hasRole('user|branch-manager')) {
            $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();
            if ($dataKaryawan) {
                $query->where('karyawan_id', $dataKaryawan->id);
            }

        // 🔹 Admin → semua karyawan di entitas
        } elseif ($user->hasRole('admin')) {
            $karyawanIdList = M_DataKaryawan::where('entitas', $entitas)->pluck('id');
            $query->whereIn('karyawan_id', $karyawanIdList);

        // 🔹 SPV → hanya karyawan di divisinya
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
        }

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

        $pengajuanDispens = $query->latest()->paginate(10);

        return view('livewire.karyawan.pengajuan.dispensasi',[
            'pengajuanDispens' => $pengajuanDispens
        ]);
    }
}
