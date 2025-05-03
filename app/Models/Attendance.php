<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'student_id',
        'teacher_id',
        'section_id',
        'subject_id',
        'date',
        'status',
    ];
    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}

