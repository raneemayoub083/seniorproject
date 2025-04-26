<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SectionSubjectTeacher;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Pass the user's role to the view
        return view('dashboard', compact('user'));
    }
    public function __invoke()
    {
        $sectionsSubjects = SectionSubjectTeacher::with(['section', 'subject', 'teacher'])->get();

        $studentCount = Student::count();
        $teacherCount = Teacher::count();
        return view('dashboard', compact('studentCount', 'teacherCount', 'sectionsSubjects'));
      
    }
}