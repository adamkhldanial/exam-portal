<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_submission_id', 'question_id',
        'option_id', 'answer_text', 'marks_awarded',
    ];

    protected $casts = [
        'exam_submission_id' => 'integer',
        'question_id' => 'integer',
        'option_id' => 'integer',
        'marks_awarded' => 'integer',
    ];

    public function submission()
    {
        return $this->belongsTo(ExamSubmission::class, 'exam_submission_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
