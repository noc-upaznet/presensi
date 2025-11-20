<?php

namespace App\Livewire\QuestionBank;

use App\Models\M_ListAnswer;
use App\Models\M_ListQuestion;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalQuestion extends Component
{
    public $name;
    public $answer1;
    public $answer2;
    public $answer3;
    public $answer4;
    public $correct_answer;

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'answer1' => 'required|string|max:255',
            'answer2' => 'required|string|max:255',
            'answer3' => 'required|string|max:255',
            'answer4' => 'required|string|max:255',
            'correct_answer' => 'required|in:answer1,answer2,answer3,answer4',
        ]);

        $question = M_ListQuestion::create([
            'name' => $this->name,
        ]);

        M_ListAnswer::insert([
            ['question_id' => $question->id, 'name' => $this->answer1, 'is_correct' => $this->correct_answer === 'answer1' ? 1 : 0],
            ['question_id' => $question->id, 'name' => $this->answer2, 'is_correct' => $this->correct_answer === 'answer2' ? 1 : 0],
            ['question_id' => $question->id, 'name' => $this->answer3, 'is_correct' => $this->correct_answer === 'answer3' ? 1 : 0],
            ['question_id' => $question->id, 'name' => $this->answer4, 'is_correct' => $this->correct_answer === 'answer4' ? 1 : 0],
        ]);

        $this->reset(['name', 'answer1', 'answer2', 'answer3', 'answer4', 'correct_answer']);
        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);

        $this->dispatch('modalAddQuestion', action: 'hide');
        $this->dispatch('refresh');
    }
    public function render()
    {
        return view('livewire.question-bank.modal-question');
    }
}
