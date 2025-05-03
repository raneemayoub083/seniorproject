<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentParent;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamStudentGrade;

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
}
