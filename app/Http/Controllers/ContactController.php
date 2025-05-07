<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{

    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            Mail::raw($validated['message'], function ($mail) use ($validated) {
                $mail->to('fatimadhaini14@email.com')
                    ->subject('Contact Form: ' . $validated['subject'])
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->replyTo($validated['email'], $validated['name']);
            });


            return response()->json([
                'status' => 'success',
                'message' => 'Your message has been sent successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'There was an error sending your message: ' . $e->getMessage(),
            ]);
        }
    }
}
