<?php

namespace App\Livewire\QuestionBank;

use Livewire\Attributes\On;
use Livewire\Component;

class QuestionBank extends Component
{
    public function showAdd()
    {
        $this->dispatch('modalAddQuestion', action: 'show');
    }

    #[On('refreshTable')]
    public function render()
    {
        $datas = \App\Models\M_ListQuestion::with('answers')->get();
        return view('livewire.question-bank.question-bank', [
            'datas' => $datas
        ]);
    }
}
