<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AcademicYear;
use App\Models\Section;
use App\Models\Subject;

class LessonController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'section_subject_teacher_id' => 'required|exists:section_subject_teacher,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'nullable|mimes:mp4,avi,mov|max:10000', // 10MB max
            'pdf' => 'nullable|mimes:pdf|max:5120', // 5MB max
        ]);

        try {
            // Upload video & PDF to storage
            $videoPath = $request->file('video') ? $request->file('video')->store('videos', 'public') : null;
            $pdfPath = $request->file('pdf') ? $request->file('pdf')->store('pdfs', 'public') : null;

            // Create Lesson
            Lesson::create([
                'section_subject_teacher_id' => $request->section_subject_teacher_id,
                'title' => $request->title,
                'description' => $request->description,
                'video' => $videoPath,
                'pdf' => $pdfPath,
            ]);

            return response()->json(['success' => 'Lesson added successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'There was an error adding the lesson.'], 500);
        }
    }
    public function showLessonsBySection($sectionId)
    {
        // Fetch lessons for the specific academic year and section
        $lessons = Lesson::whereHas('sectionSubjectTeacher.section', function ($query) use ($sectionId) {
            $query->where('id', $sectionId);
        })->get();
        $section = \App\Models\Section::find($sectionId);
        $academicYearName = $section->academicYear->name;
        $subjectName = $section->sectionSubjectTeachers->first()->subject->name;
        $sectionName = $section->name;
        // Return the view with the lessons
        return view('teacherdash.lessons.index', compact('lessons','academicYearName','subjectName','sectionName'));
    }
    public function showLessonsBySubject(Request $request)
    {
        
        // Retrieve subjectId and sectionId from the request
        $subjectId = $request->input('subjectId');
        $sectionId = $request->input('sectionId');

        // If subjectId or sectionId are not set, handle the error
        if (!$subjectId || !$sectionId) {
            abort(404, 'Subject or Section not found.');
        }

        // Fetch lessons for the specific subject and section
        $lessons = Lesson::whereHas('sectionSubjectTeacher', function ($query) use ($subjectId, $sectionId) {
            $query->where('subject_id', $subjectId)
                ->where('section_id', $sectionId)
                ->whereHas('teacher'); // Ensure the teacher relationship exists
        })->get();

        // Fetch the subject name, section name, and teacher name
        $subjectName = \App\Models\Subject::find($subjectId)->name;
        $section = \App\Models\Section::find($sectionId);
        $sectionName = $section->name;
        $academicYearName = $section->academicYear->name; // Assuming Section has a relationship with AcademicYear

        // Fetch the teacher's first and last name
        $teacher = $section->sectionSubjectTeachers->first()->teacher ?? null;
        $teacherName = $teacher ? $teacher->first_name . ' ' . $teacher->last_name : 'N/A';

        // Return the view with the lessons and additional details
        return view('studentdash.lessons.index', compact('lessons', 'subjectName', 'sectionName', 'academicYearName', 'teacherName'));
    }
}
