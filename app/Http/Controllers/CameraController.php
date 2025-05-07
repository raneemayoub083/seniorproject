<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CameraController extends Controller
{
   

    public function requestCamera($studentId)
    {
        Cache::put("camera_request_{$studentId}", true, now()->addMinutes(1));
        return response()->json(['requested' => true]);
    }

    public function checkRequest($studentId)
    {
        $stream = Cache::pull("camera_request_{$studentId}");
        return response()->json(['stream' => $stream]);
    }

    public function storeSignal(Request $request)
    {
        Cache::put("signal_from_student_{$request->student_id}", $request->signal, now()->addMinutes(2));


        Log::info('✅ Signal stored from student', [
            'student_id' => $request->student_id,
            'signal' => $request->signal
        ]);

        return response()->json(['status' => 'stored']);
    }


    public function getSignal($studentId)
    {
        $signal = Cache::get("signal_from_student_{$studentId}");
        return response()->json(['signal' => $signal]);
    }
}
