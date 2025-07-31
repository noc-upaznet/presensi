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

class PengajuanLembur extends Component
{
    public LemburForm $form;
    protected $listeners = ['refreshTable' => 'refresh'];
    public $filterPengajuan = '';
    public $filterBulan = '';
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

        $userRole = Auth::user()->current_role;
        $pengajuRole = optional(optional($pengajuan->getKaryawan)->user)->current_role;
        // dd($pengajuRole);

        // SPV approval
        if ($userRole === 'spv') {
            if ($status == 1) {
                $pengajuan->approve_spv = 1;
            } elseif ($status == 2) {
                $pengajuan->approve_spv = 2;
                $pengajuan->status = 2;
            }
        }
        
        // HR approval
        if ($userRole === 'hr') {
            if ($pengajuRole === 'spv') {
            // Jika pengaju adalah SPV, langsung bisa diapprove HR
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

        // Jika pengaju adalah HR
        elseif ($userRole === 'admin') {
            if ($pengajuRole !== 'hr') {
                $this->dispatch('swal', params: [
                    'title' => 'Tidak Diizinkan',
                    'icon' => 'error',
                    'text' => 'Admin hanya bisa menyetujui pengajuan dari HR.'
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

        // Final status = 1 jika semua approval positif
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
    
        if ($user->current_role === 'user') {
            // Jika user biasa, hanya lihat lembur miliknya
            $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();
            if ($dataKaryawan) {
                $query->where('karyawan_id', $dataKaryawan->id);
            }

        } elseif ($user->current_role === 'admin') {
            // Jika admin, filter berdasarkan entitas dari session
            $entitasModel = \App\Models\M_Entitas::where('nama', $entitas)->first();
            if ($entitasModel) {
                $karyawanIdList = M_DataKaryawan::where('entitas', $entitas)->pluck('id');
                $query->whereIn('karyawan_id', $karyawanIdList);
            }

        } elseif ($user->current_role === 'spv') {
            // Jika SPV, hanya bisa melihat karyawan dari divisinya saja
            $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();
            // dd($dataKaryawan);
            if ($dataKaryawan && $dataKaryawan->divisi) {
                $karyawanIdList = M_DataKaryawan::where('divisi', $dataKaryawan->divisi)->pluck('id');
                $query->whereIn('karyawan_id', $karyawanIdList);
            }
        } elseif ($user->current_role === 'hr') {
            // Jika HR, bisa melihat semua karyawan dari semua entitas
            $karyawanIdList = M_DataKaryawan::pluck('id');
            $query->whereIn('karyawan_id', $karyawanIdList);
        }
        // Jika HR atau SPV, tidak difilter entitas → bisa lihat semua data
    
        // Filter status pengajuan
        if (in_array($this->filterPengajuan, ['0', '1', '2'], true)) {
            $query->where('status', (int) $this->filterPengajuan);
        }
    
        // Filter bulan
        if (!empty($this->filterBulan)) {
            $bulan = date('m', strtotime($this->filterBulan));
            $tahun = date('Y', strtotime($this->filterBulan));
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }
    
        // Search
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
