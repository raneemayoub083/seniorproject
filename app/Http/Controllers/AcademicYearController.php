<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Section;
use App\Models\Teacher;

class AcademicYearController extends Controller
{
    // Display the academic years index page
    public function index()
    {
        // Get all academic years
        $academicYears = AcademicYear::all();

        // Pass the data to the view
        return view('academic_year.index', compact('academicYears'));
    }

    // Show the form for creating a new academic year
    public function create()
    {
        return view('academic_year.create');
    }

    // Store a newly created academic year in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'application_opening' => 'nullable|date',
            'application_expiry' => 'nullable|date',
            'status' => 'required|string|in:pending,opened,completed',
        ]);

        AcademicYear::create($request->all());

        return redirect()->route('academic_year.index')->with('success', 'Academic Year created successfully');
    }

    // Show the form for editing the specified academic year
    public function edit(Request $request)
    {
        $academicYear = AcademicYear::findOrFail($request->query('id'));
        return view('academic_year.edit', compact('academicYear'));
    }

    // Update the specified academic year in storage
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'application_opening' => 'nullable|date',
            'application_expiry' => 'nullable|date',
            'status' => 'required|string|in:pending,opened,completed',
        ]);

        $academicYear = AcademicYear::findOrFail($request->query('id'));
        $academicYear->update($request->all());

        return redirect()->route('academic_year.index')->with('success', 'Academic Year updated successfully');
    }

    // Remove the specified academic year from storage
    public function destroy(Request $request)
    {
        try {
            $academicYear = AcademicYear::findOrFail($request->query('id'));
            $academicYear->delete();
            return redirect()->route('academic_year.index')->with('success', 'Academic Year deleted successfully');
        } catch (\Exception $exception) {
            report($exception);
            return redirect()->route('academic_year.index')->with('error', 'There was an error deleting the Academic Year');
        }
    }
  public function showSections(Request $request)
{
    // Find the academic year by ID and load its sections
    $academicYear = AcademicYear::with('sections')->findOrFail($request->query('id'));

    // Pass the academic year to the view
    return view('academic_year.sections', compact('academicYear'));
}
public function showStudents(Request $request)
{
    // Find the section by ID and load its students
    $section = Section::with('students')->findOrFail($request->query('id'));

    // Pass the section to the view
    return view('academic_year.students', compact('section'));
}

public function showSubjects(Request $request)
{
    // Find the section by ID and load its grade and subjects
    $section = Section::with('grade.subjects')->findOrFail($request->query('id'));
    $teachers = Teacher::with('subjects')->where('status','active')->get();

    // Pass the section, subjects, and teachers to the view
    return view('academic_year.subjects', [
        'section' => $section,
        'subjects' => $section->grade->subjects,
        'teachers' => $teachers,
    ]);
}
}