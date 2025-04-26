<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disability;

class DisabilityController extends Controller
{
    // Display a listing of the disabilities
    public function index()
    {
        $disabilities = Disability::all();
        return view('disability.index', compact('disabilities'));
    }

    // Show the form for creating a new disability
    public function create()
    {
        return view('disability.create');
    }

    // Store a newly created disability in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:disabilities,name',
        ]);

        Disability::create(['name' => $request->name]);

        return redirect()->route('disability.index')->with('success', 'Disability added successfully');
    }

    // Show the form for editing the specified disability
    public function edit(Request $request)
    {
        $disability = Disability::findOrFail($request->query('id'));
        return view('disability.edit', compact('disability'));
    }

    // Update the specified disability in storage
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:disabilities,name,' . $request->query('id'),
        ]);

        $disability = Disability::findOrFail($request->query('id'));
        $disability->update(['name' => $request->name]);

        return redirect()->route('disability.index')->with('success', 'Disability updated successfully');
    }

    // Remove the specified disability from storage
    public function destroy(Request $request)
    {
        try {
            $disability = Disability::findOrFail($request->query('id'));
            $disability->delete();
            return redirect()->route('disability.index')->with('success', 'Disability deleted successfully');
        } catch (\Exception $exception) {
            report($exception);
            return redirect()->route('disability.index')->with('error', 'There was an error deleting the disability');
        }
    }
  
}
