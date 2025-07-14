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

class Pengajuan extends Component
{
    public PengajuanForm $form;
    use WithPagination;
    protected $listeners = ['refreshTable' => 'refresh'];

    public $filterPengajuan = '';
    public $filterBulan = '';
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

        $userRole = Auth::user()->current_role;

        // Update approve field berdasarkan role dan status
        if ($userRole === 'spv') {
            if ($status == 1) {
                $pengajuan->approve_spv = 1;
            } elseif ($status == 2) {
                $pengajuan->approve_spv = 2;
                $pengajuan->status = 2; // langsung ditolak jika SPV tolak
            }
        } elseif ($userRole === 'hr') {
            if ($pengajuan->approve_spv == 1) {
                if ($status == 1) {
                    $pengajuan->approve_hr = 1;
                    $pengajuan->status = 1; // misal set status jadi diterima penuh
                } elseif ($status == 2) {
                    $pengajuan->approve_hr = 2;
                    $pengajuan->status = 2;
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
        } else {
            return;
        }

        // Cek jika kedua approver sudah menyetujui, maka status jadi 1
        if ($pengajuan->approve_spv == 1 && $pengajuan->approve_hr == 1) {
            $pengajuan->status = 1;
        }

        $pengajuan->save();

        // Jika status jadi 1, update jadwal
        if ($pengajuan->status == 1) {
            $tanggal = \Carbon\Carbon::parse($pengajuan->tanggal);
            $hari = 'd' . $tanggal->day;
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
                    $hari => $pengajuan->shift_id,
                ]);
                $jadwalBaru->save();
            }
        }

        $this->dispatch('swal', params: [
            'title' => 'Status Diperbarui',
            'icon' => 'success',
            'text' => 'Status dan jadwal berhasil diperbarui.'
        ]);

        $this->dispatch('refresh');
    }


    public function render()
    {
        $query = M_Pengajuan::with(['getKaryawan', 'getShift']);
        $user = Auth::user();
        $entitas = session('selected_entitas', 'UHO'); // default ke 'UHO'

        if ($user->current_role === 'user') {
            // User biasa hanya melihat datanya sendiri
            $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();
            if ($dataKaryawan) {
                $query->where('karyawan_id', $dataKaryawan->id);
            }

        } elseif ($user->current_role === 'admin') {
            // Admin atau HR melihat semua karyawan dalam entitas
            $karyawanIdList = M_DataKaryawan::where('entitas', $entitas)->pluck('id');
            $query->whereIn('karyawan_id', $karyawanIdList);

        } elseif ($user->current_role === 'spv') {
            // SPV hanya melihat karyawan yang berada dalam divisinya dan entitas yang sama
            $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();
            if ($dataKaryawan) {
                $karyawanIdList = M_DataKaryawan::where('divisi', $dataKaryawan->divisi)
                    ->where('entitas', $dataKaryawan->entitas) // pastikan tetap dalam entitas yang sama
                    ->pluck('id');
                $query->whereIn('karyawan_id', $karyawanIdList);
            }
        }

        // Filter Status
        if (in_array($this->filterPengajuan, ['0', '1', '2'], true)) {
            $query->where('status', (int) $this->filterPengajuan);
        }

        // Filter Bulan
        if (!empty($this->filterBulan)) {
            $bulan = date('m', strtotime($this->filterBulan));
            $tahun = date('Y', strtotime($this->filterBulan));
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }

        // Search + Pagination
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                ->orWhere('tanggal', 'like', '%' . $this->search . '%');
            });
        }

        $pengajuan = $query->latest()->paginate(10);

        return view('livewire.karyawan.pengajuan.pengajuan', [
            'pengajuans' => $pengajuan,
        ]);
    }

}
