<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\AcademicYear;
use App\Models\Disability;
use App\Models\Subject;

class GradeController extends Controller
{
    // Display a listing of the grades
    public function index()
    {
        $grades = Grade::with('subjects')->get();
        return view('grade.index', compact('grades'));
    }

    // Show the form for creating a new grade
    public function create()
    {
        $subjects = Subject::all();
        return view('grade.create', compact('subjects'));
    }

    // Store a newly created grade in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $grade = Grade::create(['name' => $request->name]);
        $grade->subjects()->sync($request->subjects);

        return redirect()->route('grade.index')->with('success', 'Grade created successfully');
    }

    // Show the form for editing the specified grade
    public function edit(Request $request)
    {
        $grade= Grade::findOrFail($request->query('id'));
        $subjects = Subject::all();
        return view('grade.edit', compact('grade', 'subjects'));
    }

    // Update the specified grade in storage
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id',
        ]);
        $grade= Grade::findOrFail($request->query('id'));
        $grade->update(['name' => $request->name]);
        $grade->subjects()->sync($request->subjects);
        return redirect()->route('grade.index')->with('success', 'Grade updated successfully');
            }

    // Remove the specified grade from storage
    public function destroy(Grade $grade, Request $request)
    {
        try { 
            $grade = Grade::findOrFail($request->query('id'));
            $grade->subjects()->detach();
            $grade->delete();
            return redirect()->route('grade.index')->with('success', 'Grade deleted successfully');
        } catch (\Exception $exception) {
            report($exception);
            return redirect()->route('grade.index')->with('error', 'There was an error deleting the Grade');
        }
       
    }
    public function showApplyForm()
    {
        $disabilities = Disability::all(); // Fetch all disability types from the database
        $grades = Grade::all(); // Fetch all grades from the database
        $currentDate = now();
        $academicYear = AcademicYear::where('application_opening', '<=', $currentDate)
            ->where('application_expiry', '>=', $currentDate)
            ->first();
            
        return view('client.apply', compact('grades', 'disabilities', 'academicYear'));
    }
}