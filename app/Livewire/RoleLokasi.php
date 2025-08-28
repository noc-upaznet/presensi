<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Lokasi;
use Livewire\Component;
use App\Models\M_Entitas;
use Livewire\WithPagination;
use App\Models\M_DataKaryawan;
use App\Models\RoleLokasiModel;
use Illuminate\Support\Facades\Crypt;

class RoleLokasi extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public $lock = false;
    public $lokasi_list;
    public $lokasi_presensi = [];

    public $users;
    public $lokasis;
    public $selectedKaryawan;
    public $lokasi;
    public $karyawans;
    public $editId;

    protected $listeners = ['updatedLock'];

    public function updatedLock($value)
    {
        $this->dispatch('lock-updated', ['lock' => $value]);
    }

    public function store()
    {
        $this->validate([
            'selectedKaryawan' => 'required',
            'lokasi_presensi' => 'required|array|min:1',
        ]);

        $data = [
            'karyawan_id' => $this->selectedKaryawan,
            'lock' => $this->lock,
            'lokasi_presensi' => $this->lokasi_presensi,
        ];
        // dd($data);

        RoleLokasiModel::create($data);

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been Saved successfully'
        ]);

        // Reset form
        $this->reset(['selectedKaryawan', 'lock', 'lokasi_presensi']);

        // Tutup modal dan refresh select2
        $this->dispatch('rolePresensiModal', action: 'hide');
    }

    public function showEdit($id)
    {
        $decryptedId = Crypt::decrypt($id);
        $this->editId = $decryptedId;

        $data = RoleLokasiModel::find($decryptedId);
        // dd($data);
        if (!$data) {
            session()->flash('error', 'Data tiket tidak ditemukan!');
            return;
        }
        $this->selectedKaryawan = $data->karyawan_id;
        $this->lock = (bool) $data->lock;
        $this->lokasi_presensi = $data->lokasi_presensi; // Jika disimpan dalam bentuk JSON array

        // Dispatch event ke modal
        $this->dispatch('editRolePresensiModal', action: 'show');
    }

    public function saveEdit()
    {
        $this->validate([
            'selectedKaryawan' => 'required',
            'lokasi_presensi' => 'required|array|min:1',
        ]);

        $dataId = RoleLokasiModel::findOrFail($this->editId);
        // dd($dataId);

        $data = [
            'karyawan_id' => $this->selectedKaryawan,
            'lock' => $this->lock,
            'lokasi_presensi' => $this->lokasi_presensi,
        ];
        // dd($data);

        
        $dataId->update($data);

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been Updated successfully'
        ]);

        // Reset form
        $this->reset(['selectedKaryawan', 'lock', 'lokasi_presensi']);

        // Tutup modal dan refresh select2
        $this->dispatch('editRolePresensiModal', action: 'hide');
    }


    public function confirmHapusLokasi($id)
    {
        $this->lokasi_presensi = $id;
    }

    public function deleteLokasi()
    {
        RoleLokasiModel::find($this->lokasi_presensi)->delete();
        $this->lokasi_list = RoleLokasiModel::all();

        // Notifikasi
        $this->dispatch('lokasiTerhapus');

        // Reset data setelah penghapusan
        $this->lokasi_presensi = null;
    }

    public function mount()
    {
        // Load lokasi saat komponen pertama kali di-mount
        // $this->users = User::where('role', '!=', 'admin')->orderBy('name')->get();
        $entitasNama = session('selected_entitas', 'UHO');

        $this->karyawans = M_DataKaryawan::where('entitas', $entitasNama)
            ->whereNotIn('id', function ($query) {
                $query->select('karyawan_id')->from('role_lokasi');
            })
            ->orderBy('nama_karyawan')
            ->get();
        $this->lokasis = Lokasi::orderBy('nama_lokasi')->get();
        $this->lokasi_list = RoleLokasiModel::all();

        $this->dispatch('refreshSelect2');
    }

    public function render()
    {
        // $lokasiList = RoleLokasiModel::withwhere('nama_karyawan', 'like', '%' . $this->search . '%')->paginate(10);
        $entitasNama = session('selected_entitas', 'UHO');

        $lokasiList = RoleLokasiModel::with('getKaryawan')
            ->whereHas('getKaryawan', function ($query) use ($entitasNama) {
                $query->where('entitas', $entitasNama);
            })
            ->when($this->search, function ($query) {
                $query->whereHas('getKaryawan', function ($q) {
                    $q->where('nama_karyawan', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);
        // dd($lokasiList);
        return view('livewire.role-lokasi', [
            'lokasiList' => $lokasiList,
        ]);
    }
}