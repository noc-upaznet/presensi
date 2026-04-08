<?php

namespace App\Livewire;

use App\Models\M_DataKaryawan;
use App\Models\M_Divisi;
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

    public function mount()
    {
        $this->divisiList = M_Divisi::all();
    }

    public function render()
    {
        $entitas = session('selected_entitas', 'UHO');

        // Cek level user yang login
        $userKaryawan = M_DataKaryawan::where('user_id', auth()->id())->first();
        $isSpv = $userKaryawan && strtolower($userKaryawan->level) === 'spv';

        if ($isSpv && $userKaryawan->entitas) {
            $entitas = $userKaryawan->entitas;
        }

        $query = M_DataKaryawan::with(['pengajuanHariIni.getShift'])
            ->where('status_karyawan', '!=', 'NONAKTIF')
            ->where('deleted_at', null)
            ->where('jabatan', '!=', 'Komisaris')
            ->where('jabatan', '!=', 'Direktur')
            ->where('entitas', $entitas);

        // Jika SPV, paksa filter sesuai divisi sendiri
        if ($isSpv) {
            $divisi = strtolower($userKaryawan->divisi);

            if ($divisi === 'noc') {
                // Divisi NOC → tidak pakai entitas
                $karyawanIdList = M_DataKaryawan::where('divisi', $userKaryawan->divisi)
                    ->pluck('id');
            } elseif ($divisi === 'finance' && strtoupper($entitas) === 'UNR') {
                // Finance UNR → include semua entitas MC + divisi sendiri
                $karyawanIdList = M_DataKaryawan::where(function ($q) use ($userKaryawan) {
                    $q->whereRaw('UPPER(entitas) = ?', ['MC'])
                        ->orWhere(function ($sub) use ($userKaryawan) {
                            $sub->where('divisi', $userKaryawan->divisi)
                                ->where('entitas', $userKaryawan->entitas);
                        });
                })->pluck('id');
            } else {
                // Default SPV → filter by divisi & entitas sendiri
                $karyawanIdList = M_DataKaryawan::where('divisi', $userKaryawan->divisi)
                    ->where('entitas', $entitas)
                    ->pluck('id');
            }

            // Override query
            if ($divisi === 'noc') {
                $query = M_DataKaryawan::with(['pengajuanHariIni.getShift'])
                    ->where('status_karyawan', '!=', 'NONAKTIF')
                    ->where('deleted_at', null)
                    ->where('jabatan', '!=', 'Komisaris')
                    ->where('jabatan', '!=', 'Direktur')
                    ->whereIn('id', $karyawanIdList);
            } elseif ($divisi === 'finance' && strtoupper($entitas) === 'UNR') {
                // Finance UNR tidak filter entitas
                $query = M_DataKaryawan::with(['pengajuanHariIni.getShift'])
                    ->where('status_karyawan', '!=', 'NONAKTIF')
                    ->where('deleted_at', null)
                    ->where('jabatan', '!=', 'Komisaris')
                    ->where('jabatan', '!=', 'Direktur')
                    ->whereIn('id', $karyawanIdList);
            } else {
                $query = M_DataKaryawan::with(['pengajuanHariIni.getShift'])
                    ->where('status_karyawan', '!=', 'NONAKTIF')
                    ->where('deleted_at', null)
                    ->where('jabatan', '!=', 'Komisaris')
                    ->where('jabatan', '!=', 'Direktur')
                    ->where('entitas', $entitas)
                    ->whereIn('id', $karyawanIdList);
            }
        } elseif ($this->filterDivisi) {
            $query->where('divisi', $this->filterDivisi);
        }

        if ($this->search) {
            $query->where('nama_karyawan', 'like', '%' . $this->search . '%');
        }

        $query->whereNotIn('id', function ($q) {
            $q->select('user_id')
                ->from('presensi')
                ->where('deleted_at', null)
                ->whereDate('tanggal', now());
        });

        if ($this->mode === 'pengajuan') {
            $query->whereIn('id', function ($q) {
                $q->select('karyawan_id')
                    ->from('pengajuan')
                    ->whereIn('status', [0, 1])
                    ->where('deleted_at', null)
                    ->whereDate('tanggal', now());
            });
        }

        $datas = $query->paginate($this->perPage);

        return view('livewire.employee-absent', [
            'datas' => $datas,
            'isSpv' => $isSpv,
        ]);
    }
}
