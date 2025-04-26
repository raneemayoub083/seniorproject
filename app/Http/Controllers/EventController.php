<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Notification;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start' => 'required|date',
            'audience' => 'required|array',
        ]);
        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'audience' => json_encode($request->audience), 
        ]);

        Notification::create([
            'title' => $request->title,
            'message' => $request->description ?? '',
            'audience' => json_encode($request->audience), // Save who should receive it
        ]);

        return response()->json(['message' => 'Event created successfully!']);
    }
    // Update Event
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date',
            'audience' => 'nullable|array', // Optional when editing
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $event->title = $request->title;
        $event->description = $request->description;
        $event->start = $request->start;
        $event->end = $request->end;

        if ($request->has('audience')) {
            $event->audience = $request->audience; // Ensure audience is stored as an array
        }

        $event->save();

        return response()->json(['message' => 'Event updated successfully!']);
    }

    // Delete Event
    public function destroy($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully!']);
    }
}
