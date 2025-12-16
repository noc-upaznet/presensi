<?php

namespace App\Livewire\Karyawan;

use Carbon\Carbon;
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

class DataKaryawan extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public int $perPage = 10;
    public TambahDataKaryawanForm $form;
    public $deleteKaryawan;

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

        // Kirim data ke komponen ModalKunjungan
        $this->dispatch('edit-ticket', data: $dataKaryawan->toArray());
        // dd($this->dispatch('edit-ticket', data: $dataKaryawan->toArray()));

        // Dispatch event ke modal
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


    public function render()
    {
        $query = M_DataKaryawan::query();

        // Ambil entitas yang dipilih dari session, default ke 'UHO'
        $selectedEntitas = session('selected_entitas', 'UHO');

        // Filter berdasarkan entitas jika bukan 'All'
        if ($selectedEntitas !== 'All') {
            $query->where('entitas', $selectedEntitas);
        }

        if (in_array($this->filterStatus, ['PKWT Kontrak', 'Probation', 'OJT'], true)) {
            $query->where('status_karyawan', $this->filterStatus);
        }

        if ($this->search) {
            $datas = $query
                ->where(function ($q) {
                    $q->where('nama_karyawan', 'like', '%' . $this->search . '%')
                        ->orWhere('status_karyawan', 'like', '%' . $this->search . '%')
                        ->orWhere('entitas', 'like', '%' . $this->search . '%')
                        ->orWhere('divisi', 'like', '%' . $this->search . '%');
                })
                ->latest()
                ->paginate($this->perPage);
        } else {
            $datas = $query
                ->orderBy('nip_karyawan', 'asc')
                ->paginate($this->perPage);
        }
        return view('livewire.karyawan.data-karyawan', [
            'datas' => $datas,
        ]);
    }
}
