<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    // Teacher.php (Teacher Model)
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'profile_img',
        'cv',
        'academic_year_id',
        'user_id'
    ];


    // Defining the many-to-many relationship with the Subject model
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher');
    }

    // Defining the relationship with the AcademicYear model
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
    public function sections(){
        return $this->belongsToMany(Section::class, 'section_subject_teacher')
            ->withPivot('subject_id')
            ->withTimestamps();
    }
    public function user()
    {
        return $this->belongsTo(User::class); // Assuming the user_id links to the User model
    }
    public function sectionSubjectTeachers()
    {
        return $this->hasMany(\App\Models\SectionSubjectTeacher::class);
    }
}