<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\FaceDescriptor;

use Illuminate\Support\Facades\Auth;
class FaceDescriptorController extends Controller
{
 
    public function store(Request $request)
    {
        $request->validate([
            'descriptor' => 'required|array',
        ]);

        $user = Auth::user(); // Ensure user is logged in when registering
        FaceDescriptor::updateOrCreate(
            ['user_id' => $user->id],
            ['descriptor' => json_encode($request->descriptor)]
        );

        return response()->json(['status' => 'saved']);
    }
}
