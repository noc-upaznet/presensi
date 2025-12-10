<?php

namespace App\Imports;

use App\Models\M_ListAnswer;
use App\Models\M_ListQuestion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionImport implements ToModel, WithHeadingRow
{
    private $currentQuestion = null;

    public function model(array $row)
    {
        // Jika kolom pertanyaan TIDAK kosong → buat pertanyaan baru
        if (!empty($row['pertanyaan'])) {
            $this->currentQuestion = M_ListQuestion::create([
                'name' => $row['pertanyaan']
            ]);
        }

        // Insert jawaban (tetap masuk meski pertanyaan kosong)
        if (!empty($row['jawaban'])) {
            M_ListAnswer::create([
                'question_id' => $this->currentQuestion->id,
                'name'        => $row['jawaban'],
                'is_correct'  => !empty($row['benar']) ? 1 : null,
            ]);
        }

        return null;
    }
}
