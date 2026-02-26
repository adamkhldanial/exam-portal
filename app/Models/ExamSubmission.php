<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id', 'user_id', 'started_at', 'submitted_at',
        'score', 'total_marks', 'status',
    ];

    protected $casts = [
        'exam_id' => 'integer',
        'user_id' => 'integer',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'score' => 'integer',
        'total_marks' => 'integer',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getPercentageAttribute(): ?float
    {
        if ($this->total_marks === 0 || $this->total_marks === null) {
            return null;
        }

        return round(($this->score / $this->total_marks) * 100, 1);
    }

    public function isTimedOut(): bool
    {
        return $this->status === 'in_progress'
            && now()->gt($this->started_at->addMinutes($this->exam->time_limit));
    }

    // Auto-grade MCQ answers and calculate score
    public function calculateScore(): void
    {
        $score = 0;
        $total = 0;
        foreach ($this->answers()->with(['question', 'option'])->get() as $answer) {
            $q = $answer->question;
            $total += $q->marks;
            if ($q->isMultipleChoice() && $answer->option && $answer->option->is_correct) {
                $score += $q->marks;
            } elseif (! $q->isMultipleChoice() && $answer->marks_awarded !== null) {
                $score += $answer->marks_awarded;
            }
        }
        $this->update(['score' => $score, 'total_marks' => $total]);
    }
}
