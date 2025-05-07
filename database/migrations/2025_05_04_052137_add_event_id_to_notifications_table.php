<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Send email (you can customize this)
        Mail::raw($validated['message'], function ($mail) use ($validated) {
            $mail->to('fatimadhaini14@gmail.com')
                ->subject('Contact Form: ' . $validated['subject'])
                ->from($validated['email'], $validated['name']);
        });

        return back()->with('success', 'Your message has been sent successfully!');
    }
}
