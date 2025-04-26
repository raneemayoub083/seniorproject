<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SectionSubjectTeacher;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_subject_teacher_id',
        'title',
        'description',
        'video',
        'pdf'
    ];

    public function sectionSubjectTeacher()
    {
        return $this->belongsTo(SectionSubjectTeacher::class);
    }
}
