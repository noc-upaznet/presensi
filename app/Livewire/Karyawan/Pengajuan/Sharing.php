<?php

namespace App\Livewire\Karyawan\Pengajuan;

use App\Livewire\Forms\DispensasiForm;
use App\Models\M_DataKaryawan;
use App\Models\M_Dispensation;
use App\Models\M_Presensi;
use App\Models\M_Sharing;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Sharing extends Component
{
    use WithFileUploads;
    public DispensasiForm $form;
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public $file;
    public $filterPengajuan;
    public $filterBulan;
    public $search;

    public function mount()
    {
        $this->filterBulan = date('Y-m');
    }

    public function showAdd()
    {
        $this->dispatch('modalTambahPengajuan', action: 'show');
    }

    public function store()
    {
        $this->form->validate();

        // Validasi file kalau ada
        if ($this->file instanceof UploadedFile) {
            $this->validate([
                'file' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            ], [
                'file.max'   => 'Ukuran file maksimal 2MB.',
                'file.mimes' => 'Format file harus JPG, JPEG, PNG.',
            ]);
        }

        // Simpan file kalau ada upload
        $path = null;
        if ($this->file instanceof UploadedFile) {
            $path = $this->file->store('file-pengajuan-dispensasi', 'public');
        }

        $data = [
            'karyawan_id' => M_DataKaryawan::where('user_id', Auth::id())->value('id'),
            'date'        => $this->form->date,
            'description' => $this->form->description,
            'file'        => $path,
        ];
        dd($data);

        M_Sharing::create($data);

        // Reset input
        $this->form->reset();
        $this->file = null;

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon'  => 'success',
            'text'  => 'Data has been saved successfully'
        ]);

        $this->dispatch('modalTambahPengajuan', action: 'hide');
        $this->dispatch('refresh');
    }

    public function updateStatus($id, $status = null)
    {
        $pengajuan = M_Sharing::find($id);
        // dd($pengajuan);
        if (!$pengajuan) {
            return;
        }

        $user = Auth::user();

        // === HR approval ===
        if ($user->hasRole('hr')) {
            if ($status == 1) {
                $pengajuan->approve_hr = 1;
                $pengajuan->status     = 1;

                $this->dispatch('swal', params: [
                    'title' => 'Approved',
                    'icon'  => 'success',
                    'text'  => 'Berhasil menyetujui pengajuan.'
                ]);
            } elseif ($status == 2) {
                $pengajuan->approve_hr = 2;
                $pengajuan->status     = 2;

                $this->dispatch('swal', params: [
                    'title' => 'Rejected',
                    'icon'  => 'error',
                    'text'  => 'Berhasil menolak pengajuan.'
                ]);
            }
        }

        $pengajuan->save();

        $this->dispatch('refresh');
    }

    public function render()
    {
        $query = M_Sharing::with(['getKaryawan']);
        $user = Auth::user();
        $entitas = session('selected_entitas', 'UHO'); // default ke 'UHO'

        // 🔹 User biasa → hanya lihat datanya sendiri
        if ($user->hasRole('user|branch-manager|spv')) {
            $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();
            if ($dataKaryawan) {
                $query->where('karyawan_id', $dataKaryawan->id);
            }

            // 🔹 Admin → semua karyawan di entitas
        } elseif ($user->hasRole('admin')) {
            $karyawanIdList = M_DataKaryawan::where('entitas', $entitas)->pluck('id');
            $query->whereIn('karyawan_id', $karyawanIdList);

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
            $query->where('date', 'like', $this->filterBulan . '%');
        }

        // 🔹 Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                    ->orWhere('tanggal', 'like', '%' . $this->search . '%');
            });
        }

        $datas = $query->latest()->paginate(10);

        return view('livewire.karyawan.pengajuan.sharing', [
            'datas' => $datas
        ]);
    }
}
