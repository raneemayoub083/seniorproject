<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use App\Models\Section;
use App\Models\Subject;
use Carbon\Carbon;
use Psy\Readline\Hoa\Console;


class AttendanceController extends Controller
{
    public function calendar()
    {
        $assignments = auth()->user()->teacher->sectionSubjectTeachers()
            ->with(['section.grade', 'section.academicYear', 'subject'])

            ->get();

        return view('teacherdash.attendance.calendar', compact('assignments'));
    }


    public function events(Request $request)
    {
        $sectionId = $request->query('section_id');
        $subjectId = $request->query('subject_id');

        $records = Attendance::select('date', DB::raw('count(*) as total'))
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->groupBy('date')
            ->get()
            ->map(fn($rec) => [
                'title' => 'Attendance Taken',
                'start' => $rec->date,
                'backgroundColor' => '#28a745',
            ]);

        return response()->json($records);
    }

    public function getStudents(Request $request)
    {
        $sectionId = $request->section_id;
        $subjectId = $request->subject_id;
        $date = $request->date;

        $section = Section::with('students')->findOrFail($sectionId);
        $students = $section->students;

        $attendanceMap = Attendance::where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('date', $date)
            ->pluck('status', 'student_id');

        $isReadOnly = false;
        $attendanceDate = \Carbon\Carbon::parse($date);
        if ($attendanceDate->isPast() && $attendanceDate->diffInHours(now()) > 24) {
            $isReadOnly = true;
        }
       
        return view('teacherdash.attendance._student_toggle_list', compact('students', 'attendanceMap', 'isReadOnly'));
    }



    public function store(Request $request)
    {
        $date = $request->input('date');
        $sectionId = $request->input('section_id');
        $subjectId = $request->input('subject_id');
        $teacherId = auth()->user()->teacher->id;
        $attendances = $request->input('attendances'); // student_id => present/absent

        // Restriction: Disallow updating if the date is over 24 hours in the past
        $attendanceDate = Carbon::parse($date);
        if ($attendanceDate->isPast() && $attendanceDate->diffInHours(now()) > 24) {
            return response()->json([
                'message' => 'You cannot update attendance for dates older than 24 hours.'
            ], 403);
        }

        // Save or update attendance
        foreach ($attendances as $studentId => $status) {
            Attendance::updateOrCreate([
                'student_id' => $studentId,
                'teacher_id' => $teacherId,
                'section_id' => $sectionId,
                'subject_id' => $subjectId,
                'date' => $date,
            ], [
                'status' => $status
            ]);
        }

        return response()->json(['message' => 'Attendance saved successfully.']);
    }
   
}
