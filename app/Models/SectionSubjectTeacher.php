<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionSubjectTeacher extends Model
{
    use HasFactory;

    protected $table = 'section_subject_teacher'; // Ensure this matches your pivot table name

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id'); // Ensure section_id exists in pivot table
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id'); // Ensure subject_id exists in pivot table
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id'); // Ensure teacher_id exists in pivot table
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
