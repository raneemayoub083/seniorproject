<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sign;
use Illuminate\Support\Facades\Auth;

class SignController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'landmarks' => 'required|json',
        ]);

        \App\Models\Sign::create([
            'student_id' => auth()->id(),
            'label' => $request->label,
            'landmarks' => json_decode($request->landmarks),
        ]);

        return response()->json(['message' => 'Sign saved successfully!']);
    }

    public function load(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $signs = Sign::where('student_id', Auth::id())->get(['label', 'landmarks']);

        return response()->json($signs);
    }
}
