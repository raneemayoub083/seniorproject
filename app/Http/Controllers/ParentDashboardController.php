<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentParent;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamStudentGrade;
use App\Models\SectionSubjectTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParentDashboardController extends Controller
{
    // Standard index method (used in routes/web.php)

    public function index()
    {
        $userId = Auth::id();
        $parentId = StudentParent::where('user_id', $userId)->value('id');

        $students = Student::with(['application', 'attendances'])->where('parent_id', $parentId)->get();

        $studentCharts = $students->mapWithKeys(function ($student) {
            $activeSection = $student->activeSection();

            $filteredAttendances = collect($student->attendances)
                ->where('section_id', optional($activeSection)->id);

            $examGrades = ExamStudentGrade::with('exam.sectionSubjectTeacher.subject')
                ->where('student_id', $student->id)
                ->get()
                ->filter(function ($grade) use ($activeSection) {
                    return optional($grade->exam?->sectionSubjectTeacher)->section_id === optional($activeSection)->id;
                });

            $gradeAverages = $examGrades
                ->groupBy(fn($g) => optional($g->exam?->sectionSubjectTeacher?->subject)->name ?? 'Unknown')
                ->map(fn($group) => round($group->avg('grade'), 2));

            return [$student->id => [
                'attendance' => [
                    'present' => $filteredAttendances->where('status', 'present')->count(),
                    'absent' => $filteredAttendances->where('status', 'absent')->count()
                ],
                'grades' => $gradeAverages
            ]];
        });

        return view('parentdash.dashboard', compact('students', 'studentCharts'));
    }
    public function cameraViewer()
    {
        $userId = auth()->user()->id;
        $parent = StudentParent::where('user_id', $userId)->first();
       

        // Assuming each student has a 'parent_id' pointing to the parent's user_id
        $students = \App\Models\Student::where('parent_id', $parent->id)->get();

        return view('camera.parent', compact('students'));
    }
    
public function classesPage()
{
    $parentId = StudentParent::where('user_id', auth()->id())->value('id');
    $students = Student::with('application')->where('parent_id', $parentId)->get();

    return view('parentdash.classes', compact('students'));
}

    public function fetchStudentClasses($studentId)
    {
        $student = Student::with('sections.academicYear', 'sections.grade.subjects')

            ->where('id', $studentId)
            ->first();

        $sections = $student ? $student->sections : collect();

        return response()->json(['sections' => $sections]);
    }
    public function getSubjectInfo(Request $request)
    {
        $subjectId = $request->subject_id;
        $studentId = $request->student_id;
        $sectionId = $request->section_id;

        $teacher = SectionSubjectTeacher::with('teacher.user')
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->first()?->teacher?->user?->name;

        $exams = DB::table('exam_student_grades')
            ->join('exams', 'exams.id', '=', 'exam_student_grades.exam_id')
            ->join('events', 'exams.event_id', '=', 'events.id')
            ->join('section_subject_teacher', 'exams.section_subject_teacher_id', '=', 'section_subject_teacher.id')
            ->join('subjects', 'section_subject_teacher.subject_id', '=', 'subjects.id')
            ->where('exam_student_grades.student_id', $studentId)
            ->where('section_subject_teacher.section_id', $sectionId)
            ->where('subjects.id', $subjectId)
            ->select([
                'events.title as exam_title',
                'exam_student_grades.grade'
            ])
            ->get();

        return response()->json([
            'teacher' => $teacher,
            'exams' => $exams
        ]);
    }
}
