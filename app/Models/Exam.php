<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'subject_id', 'created_by',
        'time_limit', 'starts_at', 'ends_at', 'is_published', 'passing_score',
    ];

    protected $casts = [
        'subject_id' => 'integer',
        'created_by' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_published' => 'boolean',
        'time_limit' => 'integer',
        'passing_score' => 'integer',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function submissions()
    {
        return $this->hasMany(ExamSubmission::class);
    }

    public function getTotalMarksAttribute(): int
    {
        return $this->questions->sum('marks');
    }

    public function isAvailable(): bool
    {
        if (! $this->is_published) {
            return false;
        }
        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }
        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }

        return true;
    }
}
