<?php

namespace App\Livewire\SalarySlip;

use Livewire\Component;
use App\Models\Payroll;
use Livewire\WithPagination;

class SlipGajiBelumDibuat extends Component
{
    public bool $showModal = false;
    public $slipGajiList = [];
    public $search;
    use WithPagination;

    protected $listeners = ['openSlipGajiModal' => 'showModal'];
    protected $paginationTheme = 'bootstrap';

    // public function showModal(): void
    // {
    //     $this->slipGajiList = Payroll::where('status', 'belum')->get();
    //     $this->showModal = true;
    // }

    public function showModal(): void
    {
        $data = Payroll::where('status', 'belum')->get();

        if ($data->isEmpty()) {
            // Dummy data kalau tidak ada data dari DB
            $data = collect([
                (object)[
                    'id' => 1,
                    'nama' => 'Budi Santoso',
                    'periode' => 'Mei 2025',
                    'status' => 'belum',
                ],
                (object)[
                    'id' => 2,
                    'nama' => 'Siti Aminah',
                    'periode' => 'Mei 2025',
                    'status' => 'belum',
                ],
            ]);
        }

        $this->slipGajiList = $data;
        $this->showModal = true;
    }


    public function closeModal(): void
    {
        $this->reset('showModal', 'slipGajiList');
    }

     public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $data = Payroll::latest()->paginate(10);
        if ($this->search) {
            $data = Payroll::where('nama', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(10);
        }

        return view('livewire.salary-slip.slip-gaji-belum-dibuat', [
            'slipGajiList' => $data,
        ]);
    }
}
