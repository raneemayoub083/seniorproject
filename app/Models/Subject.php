<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'grade_subject');
    }
    public function teachers(){
        return $this->belongsToMany(Teacher::class, 'section_subject_teacher')
            ->withPivot('section_id')
            ->withTimestamps();
    }
    public function exams()
    {
        return $this->hasManyThrough(
            Exam::class,
            SectionSubjectTeacher::class,
            'subject_id',               // Foreign key on section_subject_teacher
            'section_subject_teacher_id', // Foreign key on exams table
            'id',                       // Local key on subject
            'id'                        // Local key on section_subject_teacher
        );
    }
}
