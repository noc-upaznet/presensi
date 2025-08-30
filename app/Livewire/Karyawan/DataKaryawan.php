<?php

namespace App\Livewire\Karyawan;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Facades\Crypt;
use App\Livewire\Forms\TambahDataKaryawanForm;

class DataKaryawan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;
    public int $perPage = 10;
    public TambahDataKaryawanForm $form;

    protected $listeners = [
        'entitasUpdated' => '$refresh',
        'refreshTable' => '$refresh',
    ];

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

    public function render()
    {
        $query = M_DataKaryawan::query();

        // Ambil entitas yang dipilih dari session, default ke 'UHO'
        $selectedEntitas = session('selected_entitas', 'UHO');

        // Filter berdasarkan entitas jika bukan 'All'
        if ($selectedEntitas !== 'All') {
            $query->where('entitas', $selectedEntitas);
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
                ->latest()
                ->paginate($this->perPage);
        }
        return view('livewire.karyawan.data-karyawan', [
            'datas' => $datas,
        ]);
    }
}
