<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Exam;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }


    public function fetch()
    {
        $user = Auth::user();
        $role = strtolower($user->role->name);

        // Normalize role
        $normalizedRole = match ($role) {
            'student' => 'students',
            'teacher' => 'teachers',
            'parent' => 'parents',
            default => $role
        };

        $calendarItems = [];

        // ✅ STEP 1: Add only general events (skip ones with linked exams)
        $events = Event::with('exam')->get()->filter(function ($event) use ($normalizedRole) {
            $audience = json_decode($event->audience, true);
            $isAudienceMatch = collect($audience)->map(fn($a) => strtolower($a))->contains($normalizedRole);
            $isNotExam = $event->exam === null;

            return $isAudienceMatch && $isNotExam;
        });

        foreach ($events as $event) {
            $calendarItems[] = [
                'title' => $event->title,
                'start' => $event->start,
                'end' => $event->end,
                'description' => $event->description ?? 'No details provided',
                'type' => 'Event'
            ];
        }

        // ✅ STEP 2: Add exams based on role
        $exams = Exam::with('event')->get()->filter(function ($exam) use ($normalizedRole, $user) {
            if ($normalizedRole === 'teachers') {
                return $exam->sectionSubjectTeacher->teacher_id == $user->teacher->id;
            } elseif ($normalizedRole === 'students') {
                return $exam->students->contains('id', $user->student->id);
            }
            return false;
        });

        foreach ($exams as $exam) {
            $calendarItems[] = [
                'title' => '[Exam] ' . $exam->event->title,
                'start' => $exam->event->start,
                'end' => $exam->event->end,
                'description' => $exam->event->description ?? 'No description',
                'type' => 'Exam',
            ];
        }

        return response()->json($calendarItems);
    }
}
