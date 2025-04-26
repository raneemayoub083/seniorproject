<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;

class StudentController extends Controller
{
    public function showDashboard()
    {
        // Fetch the student data along with the application and subjects
        $user=auth()->user();
        $student = Student::with('application')->where('user_id', $user->id)->first();

        // Pass the student data to the view
        return view('studentdash.dashboard', compact('student'));
    }

    public function showClasses()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Fetch the student's sections with academic years and grades
        $student = Student::with('sections.academicYear', 'sections.grade')
            ->where('user_id', $user->id)
            ->first();

        // Get all sections
        $sections = $student ? $student->sections : collect();
        


        // Pass the sections to the view
        return view('studentdash.classes', compact('sections'));
    }
    public function showActiveClass()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Fetch the student's active sections with academic years and grades
        $student = Student::with(['sections' => function ($query) {
            $query->wherePivot('status', 'active')->with('academicYear', 'grade.subjects');
        }])->where('user_id', $user->id)->first();

        // Get the active sections
        $sections = $student ? $student->sections : collect();

        // Pass the active sections to the view
        return view('studentdash.activeclass', compact('sections'));
    }
    public function viewGrades($sectionId)
    {
        $section = Section::with('grade.subjects')
            ->where('id', $sectionId)
            ->firstOrFail();

        $studentId = auth()->user()->student->id;

        $grades = DB::table('exam_student_grades')
            ->join('exams', 'exams.id', '=', 'exam_student_grades.exam_id')
            ->join('events', 'exams.event_id', '=', 'events.id') // ✅ join events table
            ->join('section_subject_teacher', 'exams.section_subject_teacher_id', '=', 'section_subject_teacher.id')
            ->join('subjects', 'section_subject_teacher.subject_id', '=', 'subjects.id')
            ->where('exam_student_grades.student_id', $studentId)
            ->where('section_subject_teacher.section_id', $sectionId)
            ->select([
                'subjects.name as subject_name',
                'events.title as exam_title',
                'exam_student_grades.grade',
                'exams.id as exam_id'
            ])
            ->get();

        return view('studentdash.grades.index', compact('section', 'grades'));
    }
    public function attendanceCalendar(Section $section)
    {
        return view('studentdash.attendance.calendar', compact('section'));
    }

    public function attendanceEvents(Section $section)
    {
        $studentId = auth()->user()->student->id;

        $records = \App\Models\Attendance::with('subject')
            ->where('student_id', $studentId)
            ->where('section_id', $section->id)
            ->get()
            ->groupBy('date');

        $events = [];

        foreach ($records as $date => $dailyRecords) {
            $titleParts = [];

            foreach ($dailyRecords as $record) {
                $emoji = $record->status === 'present' ? '✅' : '❌';
                $titleParts[] = "$emoji " . ($record->subject->name ?? 'Unknown');
            }

            $events[] = [
                'title' => implode(', ', $titleParts),
                'start' => \Carbon\Carbon::parse($date)->format('Y-m-d'), // ✅ Force string format
            ];
        }

        return response()->json($events);
    }
}