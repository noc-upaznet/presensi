<?php

namespace App\Livewire;

use App\Models\M_DataKaryawan;
use App\Models\M_Divisi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class EmployeeAbsent extends Component
{
    public $perPage = 25;
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';

    public $filterDivisi;
    public $divisiList;
    public $search;
    public $mode = 'all';
    public $selectedEntitas;
    public $currentRole;
    public $showRoleSwitcher = false;
    public $entitasList = [];
    public $roles = [];

    public function mount()
    {
        $this->selectedEntitas = session('selected_entitas', 'UHO');
        $this->divisiList = M_Divisi::all();

        $user = Auth::user();

        if ($user) {
            $this->roles = $user->roles->pluck('name')->toArray();

            // Ambil branch milik user
            $branches = $user->branches()
                ->pluck('nama', 'id')
                ->toArray();

            // Tambahkan opsi ALL di paling atas
            $this->entitasList = $branches;
        }
    }

    public function switchRole($role)
    {
        $user = User::find(Auth::id());

        if (!$user || !$user->roles->pluck('role')->contains($role)) {
            session()->flash('error', 'Kamu tidak memiliki role tersebut.');
            return;
        }

        $user->current_role = $role;
        $user->save();

        $this->currentRole = $user->current_role;

        // Redirect full-page via Livewire (bukan SPA refresh)
        return redirect(request()->header('Referer') ?? '/');
    }



    public function selectEntitas($entitas)
    {
        $this->selectedEntitas = $entitas;
        Session::put('selected_entitas', $entitas);

        // Redirect ke halaman sebelumnya atau home
        return redirect(request()->header('Referer') ?? '/');
    }

    public function render()
    {
        $entitas = session('selected_entitas', 'UHO');

        $query = M_DataKaryawan::with(['pengajuanHariIni.getShift'])
            ->where('status_karyawan', '!=', 'NONAKTIF')
            ->whereNull('deleted_at')
            ->whereNotIn('jabatan', ['Komisaris', 'Direktur'])
            ->where('entitas', $entitas);

        // Optional filter divisi (tetap dipakai)
        if ($this->filterDivisi) {
            $query->where('divisi', $this->filterDivisi);
        }

        // Search
        if ($this->search) {
            $query->where('nama_karyawan', 'like', '%' . $this->search . '%');
        }

        // Belum presensi hari ini
        $query->whereNotIn('id', function ($q) {
            $q->select('user_id')
                ->from('presensi')
                ->whereNull('deleted_at')
                ->whereDate('tanggal', now());
        });

        // Mode pengajuan
        if ($this->mode === 'pengajuan') {
            $query->whereIn('id', function ($q) {
                $q->select('karyawan_id')
                    ->from('pengajuan')
                    ->whereIn('status', [0, 1])
                    ->whereNull('deleted_at')
                    ->whereDate('tanggal', now());
            });
        }

        $datas = $query->paginate($this->perPage);

        return view('livewire.employee-absent', [
            'datas' => $datas,
        ]);
    }
}
