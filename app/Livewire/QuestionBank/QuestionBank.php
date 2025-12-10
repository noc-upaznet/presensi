<?php

namespace App\Livewire\QuestionBank;

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class QuestionBank extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';

    public function showAdd()
    {
        $this->dispatch('modalAddQuestion', action: 'show');
    }

    public function import()
    {
        $this->dispatch('modalImport', action: 'show');
    }

    public function showEdit($id)
    {
        $this->dispatch('modalEditQuestion', action: 'show', id: $id);
    }

    #[On('refreshTable')]
    public function render()
    {
        $datas = \App\Models\M_ListQuestion::with('answers')->paginate(25);
        return view('livewire.question-bank.question-bank', [
            'datas' => $datas
        ]);
    }
}
