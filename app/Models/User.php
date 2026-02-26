<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isLecturer(): bool
    {
        return $this->role === 'lecturer';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    // Student: classes enrolled in
    public function classRooms()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_student');
    }

    // Lecturer: classes created
    public function createdClasses()
    {
        return $this->hasMany(ClassRoom::class, 'created_by');
    }

    public function createdExams()
    {
        return $this->hasMany(Exam::class, 'created_by');
    }

    public function submissions()
    {
        return $this->hasMany(ExamSubmission::class);
    }
}
