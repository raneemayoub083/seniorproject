<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Teacher;

class SubjectController extends Controller
{
    public function assignTeacher(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'section_id' => 'required|exists:sections,id',
        ]);

        $subject = Subject::findOrFail($request->subject_id);
        $subject->teachers()->attach($request->teacher_id, ['section_id' => $request->section_id]);

        return redirect()->back()->with('success', 'Teacher assigned successfully.');
    }
}