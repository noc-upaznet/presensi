<?php

namespace App\Livewire\Karyawan;

use App\Exports\DataKaryawanExport;
use Livewire\Component;
use App\Models\M_Jadwal;
use Livewire\WithPagination;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Facades\Crypt;
use App\Livewire\Forms\TambahDataKaryawanForm;
use App\Models\M_AdditionalDataEmployee;
use App\Models\M_Dependents;
use App\Models\M_Dispensation;
use App\Models\M_Education;
use App\Models\M_Family;
use App\Models\M_Lembur;
use App\Models\M_Pengajuan;
use App\Models\M_Presensi;
use App\Models\M_WorkExperience;
use App\Models\PayrollModel;
use App\Models\User;
use Livewire\WithoutUrlPagination;
use Maatwebsite\Excel\Facades\Excel;

class DataKaryawan extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public int $perPage = 10;
    public TambahDataKaryawanForm $form;
    public $deleteKaryawan;
    public $deletePermanent;

    protected $listeners = [
        'entitasUpdated' => '$refresh',
        'refreshTable' => '$refresh',
    ];

    public $filterStatus = '';

    public function showEdit($id)
    {
        $this->form->resetValidation();
        $dataKaryawan = M_DataKaryawan::find(Crypt::decrypt($id));
        if (!$dataKaryawan) {
            session()->flash('error', 'Data tiket tidak ditemukan!');
            return;
        }

        $this->dispatch('edit-ticket', data: $dataKaryawan->toArray());

        $this->dispatch('modal-edit-data-karyawan', action: 'show');
    }

    public function showModalImport()
    {
        $this->dispatch('modal-import', action: 'show');
    }

    public function DetailDataKaryawan($id)
    {
        // $id = Crypt::decrypt($id);
        return redirect()->route('karyawan.detail-data-karyawan', ['id' => $id]);
    }

    public function updatedPerPage()
    {
        $this->resetPage(); // kembali ke halaman 1 jika jumlah per halaman berubah
    }
    public function entitasUpdated()
    {
        $this->resetPage();
    }

    public function confirmHapusKaryawan($id)
    {
        // dd($id);
        $this->deleteKaryawan = decrypt($id);
        // dd($this->deleteKaryawan);
        $this->dispatch('hapusKaryawanModal', action: 'show');
    }

    public function confirmDeletePermanent($id)
    {
        // dd($id);
        $this->deletePermanent = decrypt($id);
        // dd($this->deletePermanent);
        $this->dispatch('deletePermanentModal', action: 'show');
    }

    public function delete()
    {
        if ($this->deleteKaryawan) {
            // Ambil data karyawan
            $karyawan = M_DataKaryawan::find($this->deleteKaryawan);

            if ($karyawan) {
                // Hapus data yang berelasi dengan karyawan_id
                M_Jadwal::where('karyawan_id', $karyawan->id)->delete();
                M_Pengajuan::where('karyawan_id', $karyawan->id)->delete();
                PayrollModel::where('karyawan_id', $karyawan->id)->delete();
                M_Lembur::where('karyawan_id', $karyawan->id)->delete();
                M_Presensi::where('user_id', $karyawan->id)->delete();
                User::where('id', $karyawan->user_id)->delete();
                M_Dispensation::where('karyawan_id', $karyawan->id)->delete();
                M_Dependents::where('karyawan_id', $karyawan->id)->delete();
                M_AdditionalDataEmployee::where('karyawan_id', $karyawan->id)->delete();
                M_Education::where('karyawan_id', $karyawan->id)->delete();
                M_Family::where('karyawan_id', $karyawan->id)->delete();
                M_WorkExperience::where('karyawan_id', $karyawan->id)->delete();

                // 🔹 Hapus user terkait (jika ada)
                if ($karyawan->user_id) {
                    User::find($karyawan->user_id)?->delete();
                }

                // Baru hapus data karyawan
                $karyawan->delete();
            }

            $this->dispatch(
                'swal',
                params: [
                    'title' => 'Data Deleted',
                    'icon' => 'success',
                    'text' => 'Data has been deleted successfully',
                    'showConfirmButton' => false,
                    'timer' => 1500
                ]
            );
            $this->deleteKaryawan = null;
        }
    }

    public function deletePermanent1()
    {
        if (!$this->deletePermanent) {
            return;
        }


        $karyawan = M_DataKaryawan::withTrashed()
            ->find($this->deletePermanent);

        if (!$karyawan) {
            return;
        }

        $karyawanId = $karyawan->id;
        $userId     = $karyawan->user_id;

        M_Jadwal::withTrashed()->where('karyawan_id', $karyawanId)->forceDelete();
        M_Pengajuan::withTrashed()->where('karyawan_id', $karyawanId)->forceDelete();
        PayrollModel::withTrashed()->where('karyawan_id', $karyawanId)->forceDelete();
        M_Lembur::withTrashed()->where('karyawan_id', $karyawanId)->forceDelete();
        M_Presensi::withTrashed()->where('user_id', $karyawanId)->forceDelete();
        M_Dispensation::where('karyawan_id', $karyawanId)->delete();
        M_Dependents::where('karyawan_id', $karyawanId)->delete();
        M_AdditionalDataEmployee::where('karyawan_id', $karyawanId)->delete();
        M_Education::where('karyawan_id', $karyawanId)->delete();
        M_Family::withTrashed()->where('karyawan_id', $karyawanId)->forceDelete();
        M_WorkExperience::where('karyawan_id', $karyawanId)->delete();

        if ($userId) {
            User::withTrashed()->where('id', $userId)->forceDelete();
        }

        $karyawan->forceDelete();

        $this->dispatch(
            'swal',
            params: [
                'title' => 'Data Deleted',
                'icon' => 'success',
                'text' => 'Data berhasil dihapus secara permanen',
                'showConfirmButton' => false,
                'timer' => 1500
            ]
        );

        $this->deletePermanent = null;
    }

    public function exportDataKaryawan()
    {
        $selectedEntitas = session('selected_entitas', 'UHO');

        return Excel::download(
            new DataKaryawanExport($selectedEntitas),
            'data_karyawan_' . $selectedEntitas . '.xlsx'
        );
    }


    // public function render()
    // {
    //     $query = M_DataKaryawan::query();

    //     // Ambil entitas yang dipilih dari session, default ke 'UHO'
    //     $selectedEntitas = session('selected_entitas', 'UHO');

    //     // Filter berdasarkan entitas jika bukan 'All'
    //     if ($selectedEntitas !== 'All') {
    //         $query->where('entitas', $selectedEntitas);
    //     }

    //     if (in_array($this->filterStatus, ['PKWT Kontrak', 'Probation', 'OJT'], true)) {
    //         $query->where('status_karyawan', $this->filterStatus);
    //     }

    //     if ($this->search) {
    //         $datas = $query
    //             ->where(function ($q) {
    //                 $q->where('nama_karyawan', 'like', '%' . $this->search . '%')
    //                     ->orWhere('status_karyawan', 'like', '%' . $this->search . '%')
    //                     ->orWhere('entitas', 'like', '%' . $this->search . '%')
    //                     ->orWhere('divisi', 'like', '%' . $this->search . '%');
    //             })
    //             ->latest()
    //             ->paginate($this->perPage);
    //     } else {
    //         $datas = $query
    //             ->orderBy('nip_karyawan', 'asc')
    //             ->paginate($this->perPage);
    //     }
    //     return view('livewire.karyawan.data-karyawan', [
    //         'datas' => $datas,
    //     ]);
    // }

    public function render()
    {
        $query = M_DataKaryawan::query();
        $user  = auth()->user();

        // Data karyawan milik user login
        $karyawanUser = M_DataKaryawan::where('user_id', $user->id)->first();

        // Entitas dari session (fallback)
        $selectedEntitas = session('selected_entitas', 'UHO');

        if (
            $user->hasRole('spv-teknisi') &&
            $karyawanUser->divisi === 'Teknisi' &&
            $karyawanUser->entitas === 'UNR'
        ) {
            // Tampilkan: Teknisi di UNR
            $query->where('divisi', 'Teknisi')
                ->where('entitas', 'UNR');
        } else {
            if ($selectedEntitas !== 'All') {
                $query->where('entitas', $selectedEntitas);
            }
        }

        if (in_array($this->filterStatus, ['PKWT Kontrak', 'Probation', 'OJT'], true)) {
            $query->where('status_karyawan', $this->filterStatus);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_karyawan', 'like', '%' . $this->search . '%')
                    ->orWhere('status_karyawan', 'like', '%' . $this->search . '%')
                    ->orWhere('entitas', 'like', '%' . $this->search . '%')
                    ->orWhere('divisi', 'like', '%' . $this->search . '%');
            });
        }

        $datas = $query
            ->orderBy('nip_karyawan', 'asc')
            ->paginate($this->perPage);

        return view('livewire.karyawan.data-karyawan', [
            'datas' => $datas,
        ]);
    }
}
