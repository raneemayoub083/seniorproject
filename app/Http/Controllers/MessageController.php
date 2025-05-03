<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\StudentParent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class MessageController extends Controller
{
    public function index($receiverId = null)
    {
        $user = Auth::user();
        $contacts = collect();

        // Role: Parent
        if ($user->role->name === 'Parent') {
            $parentId = StudentParent::where('user_id', $user->id)->value('id');
            $students = Student::with('sections.academicYear')->where('parent_id', $parentId)->get();

            foreach ($students as $student) {
                $activeSection = $student->activeSection();

                if ($activeSection) {
                    $ssts = \App\Models\SectionSubjectTeacher::with(['teacher.user', 'subject'])
                        ->where('section_id', $activeSection->id)
                        ->get();

                    foreach ($ssts as $record) {
                        if ($record->teacher && $record->teacher->user) {
                            $contacts->push([
                                'user' => $record->teacher->user,
                                'subject' => $record->subject->name ?? 'N/A',
                            ]);
                        }
                    }
                }
            }

            $contacts = $contacts->unique(fn($item) => $item['user']->id)->values();
        }

        // Role: Teacher
        elseif ($user->role->name === 'Teacher') {
            $teacher = Teacher::where('user_id', $user->id)->first();

            $sectionSubjects = \App\Models\SectionSubjectTeacher::where('teacher_id', $teacher->id)
                ->with(['section.students.parent.user', 'section.grade'])
                ->get();

            $parentMap = [];

            foreach ($sectionSubjects as $sst) {
                $section = $sst->section;

                foreach ($section->students ?? [] as $student) {
                    $parent = $student->parent->user ?? null;
                    $gradeName = $section->grade->name ?? 'Unknown Class';

                    if ($parent) {
                        $parentId = $parent->id;

                        if (!isset($parentMap[$parentId])) {
                            $parentMap[$parentId] = [
                                'user' => $parent,
                                'children' => [],
                            ];
                        }

                        $childName = $student->application->first_name . ' ' . $student->application->last_name;
                        $parentMap[$parentId]['children'][] = "{$childName} ({$gradeName})";
                    }
                }
            }

            $contacts = collect();

            foreach ($parentMap as $parentData) {
                $contacts->push([
                    'user' => $parentData['user'],
                    'subject' => 'Parent of: ' . implode(' — ', $parentData['children']),
                ]);
            }
        } else {
            abort(403, 'Unauthorized');
        }

        // Load receiver if any
        $receiver = $receiverId ? User::findOrFail($receiverId) : null;

        // Fetch messages
        $messages = $receiver ? Message::where(function ($query) use ($user, $receiver) {
            $query->where('sender_id', $user->id)->where('receiver_id', $receiver->id);
        })->orWhere(function ($query) use ($user, $receiver) {
            $query->where('sender_id', $receiver->id)->where('receiver_id', $user->id);
        })->orderBy('created_at')->get() : collect();

        // Mark as read
        if ($receiver) {
            Message::where('sender_id', $receiver->id)
                ->where('receiver_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        // Count unread messages per sender
        $unreadCounts = Message::select('sender_id', \DB::raw('COUNT(*) as count'))
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id');

        return view('chat.index', compact('contacts', 'receiver', 'messages', 'unreadCounts'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return redirect()->route('chat.index', $request->receiver_id)->with('success', 'Message sent!');
    }
}
