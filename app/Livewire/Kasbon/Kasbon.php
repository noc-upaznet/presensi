<?php

namespace App\Livewire\Kasbon;

use App\Models\M_DataKaryawan;
use App\Models\KasbonModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Kasbon extends Component
{
    public $karyawans;
    public $selectedKaryawan = null;
    public $showForm = false;

    public $editId = null;
    public $isEdit = false;

    public $showDetails = false;
    public $detailKasbon = null;
    public $detailRiwayat = [];

    public $form = [
        'karyawan_id'     => '',
        'total_kasbon'    => 0,
        'jml_angsuran'    => 1,
        'kasbon_perbulan' => 0,
        'tanggal_kasbon'  => '',
        'mulai_potong'    => '',
        'keterangan'      => '',
    ];

    /**
     * INIT
     */
    public function mount()
    {
        $entitas = session('selected_entitas', 'UHO');

        $this->karyawans = M_DataKaryawan::where('entitas', $entitas)
            ->orderBy('nama_karyawan')
            ->get();
    }

    /**
     * SHOW MODAL
     */
    public function showAdd()
    {
        $this->resetForm();
        // $this->showForm = true;
        $this->dispatch('modal-add-edit', action: 'show');
    }

    public function showEdit($id)
    {
        $kasbon = KasbonModel::findOrFail($id);

        $this->editId = $kasbon->id;
        $this->isEdit = true;

        $this->form = [
            'karyawan_id'     => $kasbon->karyawan_id,
            'total_kasbon'    => number_format($kasbon->total_kasbon, 0, ',', '.'),
            'jml_angsuran'    => $kasbon->jumlah_angsuran,
            'kasbon_perbulan' => number_format($kasbon->kasbon_perbulan, 0, ',', '.'),
            'tanggal_kasbon'  => $kasbon->tanggal_kasbon,
            'mulai_potong'    => \Carbon\Carbon::parse($kasbon->mulai_potong)->format('Y-m'),
            'keterangan'      => $kasbon->keterangan,
        ];

        $this->selectedKaryawan = M_DataKaryawan::find($kasbon->karyawan_id);

        // $this->showForm = true;
        $this->dispatch('modal-add-edit', action: 'show');
    }

    public function closeForm()
    {
        $this->reset(['showForm', 'isEdit', 'editId']);
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->form = [
            'karyawan_id'     => '',
            'total_kasbon'    => 0,
            'jml_angsuran'    => 1,
            'kasbon_perbulan' => 0,
            'tanggal_kasbon'  => now()->toDateString(),
            'mulai_potong'    => now()->format('Y-m'),
            'keterangan'      => '',
        ];

        $this->selectedKaryawan = null;
    }

    public function showDetail($id)
    {
        $kasbon = KasbonModel::with('detail', 'karyawan')->findOrFail($id);

        $this->detailKasbon = $kasbon;
        $this->detailRiwayat = $kasbon->detail()->orderBy('periode')->get();

        // $this->showDetails = true;
        $this->dispatch('modal-detail', action: 'show');
    }

    public function closeDetail()
    {
        $this->reset(['showDetails', 'detailKasbon', 'detailRiwayat']);
    }

    public function updatedFormKaryawanId($id)
    {
        $this->selectedKaryawan = M_DataKaryawan::find($id);
    }

    public function updatedFormTotalKasbon()
    {
        $this->hitungKasbon();
    }

    public function updatedFormJmlAngsuran()
    {
        $this->hitungKasbon();
    }

    private function hitungKasbon()
    {
        $total = (float) preg_replace('/[^0-9]/', '', $this->form['total_kasbon']);
        $bulan = (int) $this->form['jml_angsuran'];

        if ($total > 0 && $bulan > 0) {
            $perBulan = round($total / $bulan, 2);
            $this->form['kasbon_perbulan'] = number_format($perBulan, 2, ',', '.');
        } else {
            $this->form['kasbon_perbulan'] = 0;
        }
    }

    public function save()
    {
        $this->validate([
            'form.karyawan_id'  => 'required|exists:data_karyawan,id',
            'form.total_kasbon' => 'required',
            'form.jml_angsuran' => 'required|integer|min:1',
        ]);

        // bersihkan rupiah
        $total = str_replace('.', '', $this->form['total_kasbon']);
        $total = str_replace(',', '.', $total);

        $perBulan = str_replace('.', '', $this->form['kasbon_perbulan']);
        $perBulan = str_replace(',', '.', $perBulan);

        $data = [
            'karyawan_id'     => $this->form['karyawan_id'],
            'total_kasbon'    => $total,
            'kasbon_perbulan' => $perBulan,
            'jumlah_angsuran' => $this->form['jml_angsuran'],
            'tanggal_kasbon'  => $this->form['tanggal_kasbon'],
            'mulai_potong'    => $this->form['mulai_potong'] . '-01',
            'keterangan'      => $this->form['keterangan'],
        ];

        if ($this->isEdit) {
            $kasbon = KasbonModel::findOrFail($this->editId);

            // hitung ulang sisa kasbon proporsional
            $selisih = $total - $kasbon->total_kasbon;
            $data['sisa_kasbon'] = $kasbon->sisa_kasbon + $selisih;

            $kasbon->update($data);

            $this->dispatch(
                'swal',
                params: [
                    'title' => 'Data Updated',
                    'icon' => 'success',
                    'text' => 'Data has been updated successfully'
                ]
            );
        } else {
            $data['sisa_kasbon'] = $total;
            $data['status'] = 'aktif';

            KasbonModel::create($data);

            $this->dispatch(
                'swal',
                params: [
                    'title' => 'Data Saved',
                    'icon' => 'success',
                    'text' => 'Data has been saved successfully'
                ]
            );
        }

        $this->reset(['showForm', 'isEdit', 'editId']);

        $this->dispatch('modal-add-edit', action: 'hide');
    }


    public function delete($id)
    {
        KasbonModel::findOrFail($id)->delete();

        $this->dispatch(
            'swal',
            params: [
                'title' => 'Data Deleted',
                'icon' => 'success',
                'text' => 'Data has been deleted successfully'
            ]
        );
    }

    public function render()
    {
        $kasbons = KasbonModel::with('karyawan')
            ->latest()
            ->get();
        return view('livewire.kasbon.kasbon', [
            'kasbons' => $kasbons,
        ]);
    }
}
