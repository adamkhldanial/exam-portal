<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description', 'created_by'];

    protected $casts = [
        'created_by' => 'integer',
    ];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'class_student')
            ->where('role', 'student');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
