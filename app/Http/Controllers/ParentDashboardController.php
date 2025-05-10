<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Student;
use App\Models\StudentParent;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamStudentGrade;
use App\Models\SectionSubjectTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Section;
use App\Models\AcademicYear;
use App\Models\Grade;

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
        $student = Student::with([
            'sections' => function ($q) {
                $q->with('academicYear', 'grade.subjects')
                    ->withPivot('status', 'final_grade');
            }
        ])->find($studentId);

        $sections = $student ? $student->sections : collect();

        // ✅ Return a next academic year that is open for applications
        $nextAcademicYear = \App\Models\AcademicYear::where('start_date', '>', now())
            ->where('application_opening', '<=', now())
            ->orderBy('start_date')
            ->first();

        return response()->json([
            'sections' => $sections,
            'nextAcademicYear' => $nextAcademicYear
        ]);
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
    public function generateReportCard(Student $student, Section $section)
    {
        // Optional: ensure the parent owns the student
        $parentId = StudentParent::where('user_id', auth()->id())->value('id');
        if ($student->parent_id != $parentId) {
            abort(403, 'Unauthorized access to student.');
        }

        // Load exams
        $exams = ExamStudentGrade::with(['exam.event', 'exam.sectionSubjectTeacher.subject'])
            ->where('student_id', $student->id)
            ->whereHas('exam.sectionSubjectTeacher', function ($q) use ($section) {
                $q->where('section_id', $section->id);
            })->get();

        // Get section_student pivot row
        $sectionStudent = DB::table('section_student')
            ->where('student_id', $student->id)
            ->where('section_id', $section->id)
            ->first();

        return view('parentdash.report-card', compact('student', 'section', 'exams', 'sectionStudent'));
    }
    public function downloadReportCardPdf(Student $student, Section $section)
    {
        $parentId = StudentParent::where('user_id', auth()->id())->value('id');
        if ($student->parent_id != $parentId) {
            abort(403, 'Unauthorized access.');
        }

        $exams = ExamStudentGrade::with(['exam.event', 'exam.sectionSubjectTeacher.subject'])
            ->where('student_id', $student->id)
            ->whereHas('exam.sectionSubjectTeacher', function ($q) use ($section) {
                $q->where('section_id', $section->id);
            })->get();

        $sectionStudent = DB::table('section_student')
            ->where('student_id', $student->id)
            ->where('section_id', $section->id)
            ->first();

        $pdf = Pdf::loadView('parentdash.report-card-pdf', compact('student', 'section', 'exams', 'sectionStudent'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('report_card_' . $student->application->first_name . '_' . $section->grade->name . '.pdf');
    }
    public function enrollToNextGrade(Student $student, Section $currentSection)
    {
        $parentId = StudentParent::where('user_id', auth()->id())->value('id');
        if ($student->parent_id != $parentId) {
            abort(403, 'Unauthorized action.');
        }

        // ✅ Check if the student passed the current section
        $pivot = DB::table('section_student')
            ->where('student_id', $student->id)
            ->where('section_id', $currentSection->id)
            ->first();

        if (!$pivot || $pivot->status !== 'pass') {
            return response()->json(['message' => 'Student must have passed to be promoted.'], 422);
        }

        // ✅ Find the next academic year (open for enrollment)
        $nextYear = AcademicYear::where('start_date', '>', now())
            ->where('application_opening', '<=', now())
            ->orderBy('start_date')
            ->first();

        if (!$nextYear) {
            return response()->json(['message' => 'No academic year open for enrollment.'], 422);
        }

        // ✅ Find the next grade
        $nextGrade = Grade::where('id', '>', $currentSection->grade_id)
            ->orderBy('id')
            ->first();

        if (!$nextGrade) {
            return response()->json(['message' => 'Next grade not found.'], 422);
        }

        // ✅ Check if a section already exists
        $nextSection = Section::where('grade_id', $nextGrade->id)
            ->where('academic_year_id', $nextYear->id)
            ->first();

        if ($nextSection) {
            // ✅ Prevent duplicate enrollment
            $alreadyEnrolled = DB::table('section_student')
                ->where('student_id', $student->id)
                ->where('section_id', $nextSection->id)
                ->exists();

            if ($alreadyEnrolled) {
                return response()->json(['message' => 'Student is already enrolled in the next grade.'], 200);
            }

            // ✅ Increment section capacity by 1
            $nextSection->increment('capacity');
        } else {
            // ✅ Create new section with initial capacity 1
            $nextSection = Section::create([
                'grade_id' => $nextGrade->id,
                'academic_year_id' => $nextYear->id,
                'name' => $nextGrade->name . ' - A',
                'capacity' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // ✅ Enroll the student
        $student->sections()->attach($nextSection->id, [
            'academic_year_id' => $nextYear->id,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Student enrolled in next grade successfully.']);
    }
}
