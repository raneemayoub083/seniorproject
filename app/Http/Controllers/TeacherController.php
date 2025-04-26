<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Storage;
use App\Mail\WelcomeTeacherMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Section;
use App\Models\SectionSubjectTeacher;
use App\Models\User;
use App\Models\Role;

class TeacherController extends Controller
{
public function create()
{
$subjects = Subject::all();
$academicYears = AcademicYear::all();
return view('teacher.create', compact('subjects', 'academicYears'));
}
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers', // Email from form
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'profile_img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cv' => 'nullable|mimes:pdf,doc,docx|max:2048',
            'academic_year_id' => 'required|exists:academic_years,id',
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        // Handle file uploads
        $profileImgPath = $request->hasFile('profile_img')
            ? $request->file('profile_img')->store('uploads', 'public')
            : null;

        $cvPath = $request->hasFile('cv')
            ? $request->file('cv')->store('uploads', 'public')
            : null;


        // Generate a random password
        $baseEmail = strtolower($request->first_name . '.' . $request->last_name);
        $domain = '@school.com';
        $email = $baseEmail . $domain;
        $counter = 1;

        while (User::where('email', $email)->exists()) {
            $email = $baseEmail . $counter . $domain;
            $counter++;
        }

        $password = strtolower(Str::random(10));

        // Create the teacher record (store the teacher data)
        $teacher = Teacher::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,  // Use the email from the form
            'phone' => $request->phone,
            'address' => $request->address,
            'profile_img' => $profileImgPath,
            'cv' => $cvPath,
            'academic_year_id' => $request->academic_year_id,
        ]);

        // Attach the subjects to the teacher
        $teacher->subjects()->attach($request->subjects);

        // Create the User record for the teacher with the email from the form and generated password
        $user = User::create([
            'name' => $teacher->first_name . ' ' . $teacher->last_name,
            'email' => $email,  // Use the email from the form
            'password' => bcrypt($password), // Encrypt the password
            'role_id' => 2  // Assuming role_id 2 is for 'Teacher'
        ]);
        $teacher->user_id = $user->id;
        $teacher->save();
        // Send the credentials to the teacher via email
        Mail::to($teacher->email)->send(new WelcomeTeacherMail($teacher, $password,$email));

        // Return success message
        return redirect()->route('teacher.index')->with('success', 'Teacher added successfully and email sent.');
    }

    public function index()
{
$teachers = Teacher::with('subjects', 'academicYear')->get();
return view('teacher.index', compact('teachers'));
}

    public function destroy(Request $request)
    {
        try {
            // Retrieve the teacher using the 'id' from the query parameter
            $teacher = Teacher::findOrFail($request->query('id'));

            // If the teacher has a profile image, delete it
            if ($teacher->profile_img) {
                Storage::delete($teacher->profile_img);
            }

            // If the teacher has a CV, delete it
            if ($teacher->cv) {
                Storage::delete($teacher->cv);
            }

            // Detach the subjects associated with this teacher
            $teacher->subjects()->detach();

            // Delete the teacher from the database
            $teacher->delete();

            // Redirect back with a success message
            return redirect()->route('teacher.index')->with('success', 'Teacher deleted successfully.');
        } catch (\Exception $exception) {
            // Log the error and redirect back with an error message
            report($exception);
            return redirect()->route('teacher.index')->with('error', 'There was an error deleting the Teacher.');
        }
    }
    public function updateStatus($id, Request $request)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->status = $request->status;
        $teacher->save();

        return response()->json(['success' => true]);
    }
    public function showDashboard()
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        if (!$teacher) {
            return redirect('/')->with('error', 'Teacher not found.');
        }
        return view('teacherdash.dashboard', compact('teacher'));
       
    }


    public function showClasses()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get the teacher instance associated with the authenticated user
        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return redirect('/')->with('error', 'Teacher not found.');
        }

        // Get the teacher's assigned sections with academic year, grade, and subject-teacher pivot
        $classes = Section::whereHas('sectionSubjectTeachers', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })
            ->with(['academicYear', 'grade', 'sectionSubjectTeachers']) // Eager load relationships
            ->get();

        // Return the view with the necessary data
        return view('teacherdash.classes', compact('teacher', 'classes'));
    }
}
