<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = strtolower($user->role->name);

        $query = Notification::query();

        if ($role === 'teacher') {
            $teacherId = $user->teacher->id;

            $teacherExamEventIds = Exam::whereHas('sectionSubjectTeacher', function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })->pluck('event_id')->toArray();

            $query->where(function ($q) use ($teacherExamEventIds) {
                $q->whereJsonContains('audience', 'teachers')
                    ->orWhereIn('event_id', $teacherExamEventIds)
                    ->orWhere(function ($sub) {
                        $sub->whereNull('event_id')
                            ->whereJsonContains('audience', 'teachers');
                    })
                    ->orWhere(function ($sub) use ($teacherExamEventIds) {
                        $sub->whereNull('event_id')
                        
                            ->where('audience', 'like', '%teachers%');
                    });
            });
        } elseif ($role === 'student') {
            $studentId = $user->student->id;

            $studentExamEventIds = Exam::whereHas('students', function ($q) use ($studentId) {
                $q->where('students.id', $studentId);
            })->pluck('event_id')->toArray();

            $query->where(function ($q) use ($studentExamEventIds) {
                $q->whereJsonContains('audience', 'students')
                    ->orWhereIn('event_id', $studentExamEventIds)
                    ->orWhere(function ($sub) {
                        $sub->whereNull('event_id')
                        ->where('audience', 'like', '%students%');
                    });
            });
        } elseif ($role === 'parent') {
            $parentId = $user->parent->id;
          
           
            // Same structure as teacher/student
            $query->where(function ($q) use ($parentId) {
                $q->whereNull('event_id')
                  ->where('audience', 'like', '%parents%');
                    });
          
        } else {
            $query->whereJsonContains('audience', $role);
        }

        return response()->json($query->latest()->take(10)->get());
    }




    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = true;
        $notification->save();

        return response()->json(['message' => 'Notification marked as read']);
    }
}
