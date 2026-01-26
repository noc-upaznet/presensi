<?php

namespace App\Livewire\Karyawan\Pengajuan;

use App\Livewire\Forms\DispensasiForm;
use App\Models\M_DataKaryawan;
use App\Models\M_Dispensation;
use App\Models\M_Presensi;
use App\Traits\CutoffPayrollTrait;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Dispensasi extends Component
{
    use CutoffPayrollTrait;
    use WithFileUploads;
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public DispensasiForm $form;
    public $file;
    public $filterPengajuan;
    public $filterBulan;
    public $search;
    public $editId;
    public $oldFile;

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

        M_Dispensation::create($data);

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

    public function showEdit($id)
    {
        $pengajuan = M_Dispensation::find(decrypt($id));
        $this->editId = $id;

        if (!$pengajuan) {
            return;
        }

        $user = Auth::user();
        $dataKaryawan = M_DataKaryawan::where('user_id', $user->id)->first();

        if ($dataKaryawan && $pengajuan->karyawan_id == $dataKaryawan->id) {
            $this->form->date        = Carbon::parse($pengajuan->date)->format('Y-m-d');
            $this->form->description = $pengajuan->description;

            $this->file = null;
            $this->oldFile = $pengajuan->file;

            $this->dispatch('modalEditPengajuan', action: 'show');
        }
    }

    public function saveEdit()
    {
        $pengajuan = M_Dispensation::find(decrypt($this->editId));
        if (!$pengajuan) {
            return;
        }

        $path = null;

        // kalau ada upload file baru
        if ($this->file instanceof \Illuminate\Http\UploadedFile) {
            $this->validate([
                'file' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            ]);

            // hapus file lama kalau ada
            if ($pengajuan->file && Storage::disk('public')->exists(str_replace('storage/', '', $pengajuan->file))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $pengajuan->file));
            }

            // simpan file baru
            $path = $this->file->store('file-pengajuan-dispensasi', 'public');
        }

        $data = [
            'date'        => $this->form->date,
            'description' => $this->form->description,
            'file'        => $path
                ? str_replace('public/', 'storage/', $path)
                : $this->oldFile,
        ];

        $pengajuan->update($data);

        $this->form->reset();
        $this->file = null;
        $this->oldFile = null;

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon'  => 'success',
            'text'  => 'Data has been updated successfully'
        ]);
        $this->dispatch('modalEditPengajuan', action: 'hide');
    }

    public function removeOldFile()
    {
        if ($this->oldFile && Storage::disk('public')->exists(str_replace('storage/', '', $this->oldFile))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $this->oldFile));
        }
        $this->oldFile = null;
    }


    public function updateStatus($id, $status = null)
    {
        $pengajuan = M_Dispensation::find($id);

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

        if ($pengajuan->status == 1) {
            M_Presensi::where('user_id', $pengajuan->karyawan_id)
                ->whereDate('tanggal', $pengajuan->date)
                ->update([
                    'previous_status' => DB::raw('status'),
                    'status'          => 2
                ]);
        }

        $this->dispatch('refresh');
    }

    public function delete($id)
    {
        $pengajuan = M_Dispensation::findOrFail(Crypt::decrypt($id));
        // dd($pengajuan);
        if (!$pengajuan) return;

        $pengajuan->delete();

        $this->dispatch('swal', params: [
            'title' => 'Pengajuan Dihapus',
            'icon' => 'success',
            'text' => 'Data pengajuan dihapus.'
        ]);

        $this->dispatch('modal-confirm-delete', action: 'hide');
        $this->dispatch('refresh');
    }

    public function render()
    {
        $query = M_Dispensation::with(['getKaryawan']);
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

        if (!empty($this->filterBulan)) {
            $year  = Carbon::parse($this->filterBulan)->year;
            $month = Carbon::parse($this->filterBulan)->month;
        } else {
            $year  = now()->year;
            $month = now()->month;
        }

        // resolve cutoff 26–25
        $cutoff = $this->resolveCutoff($year, $month, 'cutoff_25');

        $cutoffStart = $cutoff['start'];
        $cutoffEnd   = $cutoff['end'];

        // 🔹 Filter Bulan
        $query->whereBetween('date', [
            $cutoffStart->toDateTimeString(),
            $cutoffEnd->toDateTimeString(),
        ]);

        // 🔹 Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                    ->orWhere('date', 'like', '%' . $this->search . '%');
            });
        }

        $pengajuanDispens = $query->latest()->paginate(25);

        $tanggalList = $pengajuanDispens->pluck('date')->unique();

        $karyawanIdList = M_DataKaryawan::where('entitas', $entitas)->pluck('id');

        $presensiClockIn = M_Presensi::whereIn('user_id', $karyawanIdList)
            ->whereIn('tanggal', $tanggalList) // sesuai tanggal pengajuan
            ->whereNotNull('clock_in')
            ->get()
            ->groupBy(function ($item) {
                return $item->user_id . '-' . $item->tanggal;
            });

        return view('livewire.karyawan.pengajuan.dispensasi', [
            'pengajuanDispens' => $pengajuanDispens,
            'presensiClockIn' => $presensiClockIn,
        ]);
    }
}
