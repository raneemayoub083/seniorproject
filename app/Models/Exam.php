<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'section_subject_teacher_id',
        'exam_document_path',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function sectionSubjectTeacher()
    {
        return $this->belongsTo(SectionSubjectTeacher::class);
    }
    
    public function teacherExams()
    {
        $teacherId = Auth::id(); // current logged-in teacher ID

        // Fetch exams where the logged-in teacher is assigned
        $exams = Exam::whereHas('sectionSubjectTeacher', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->with(['event', 'sectionSubjectTeacher.section', 'sectionSubjectTeacher.subject'])->get();

        return view('teacher.exams.index', compact('exams'));
    }

    public function uploadDocument(Request $request, Exam $exam)
    {
        $request->validate([
            'exam_document' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10 MB max
        ]);

        $path = $request->file('exam_document')->store('exam_documents');

        $exam->update([
            'exam_document_path' => $path,
        ]);

        return response()->json([
            'message' => 'Exam document uploaded successfully!',
        ]);
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'exam_student_grades', 'exam_id', 'student_id')
            ->withPivot('grade')
            ->withTimestamps();
    }
}