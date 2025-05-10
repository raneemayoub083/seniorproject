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
        $emailBody = "
            New message from Vision Voice contact form:

            Name: {$validated['name']}
            Email: {$validated['email']}
            Subject: {$validated['subject']}

            Message:
            {$validated['message']}
        ";

        Mail::raw($emailBody, function ($mail) use ($validated) {
            $mail->to('fatimadhaini14@gmail.com') // ✅ fixed recipient address
                ->subject('Contact Form: ' . $validated['subject'])
                ->from(config('mail.from.address'), config('mail.from.name')) // ✅ your verified Brevo sender
                ->replyTo($validated['email'], $validated['name']); // ✅ user’s email shown when replying
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Your message has been sent successfully!',
            'debug' => [
                'mail_to' => 'fatimadhaini14@gmail.com',
                'mail_from' => config('mail.from.address'),
                'mail_name' => config('mail.from.name')
            ]
        ]);
    } catch (\Exception $e) {
         return response()->json([
            'status' => 'error',
            'message' => 'There was an error sending your message.',
            'debug' => $e->getMessage()
        ]);
    }
}


}
