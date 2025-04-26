<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['academic_year_id', 'grade_id', 'capacity'];

    public function applications()
    {
        return $this->belongsToMany(Application::class, 'application_section');
    }
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // Define the relationship with Grade
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'section_student')
            ->withPivot('academic_year_id')
            ->withTimestamps();
    }
    public function teachers(){
        return $this->belongsToMany(Teacher::class, 'section_subject_teacher')
            ->withPivot('subject_id')
            ->withTimestamps();
    }
    public function sectionSubjectTeachers()
    {
        return $this->hasMany(SectionSubjectTeacher::class, 'section_id');
    }
}
