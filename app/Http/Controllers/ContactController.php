<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'service' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:3000'],
            'website' => ['prohibited'],
        ]);

        Mail::send('emails.contact-request', ['data' => $data], function ($mail) use ($data) {
            $mail->to(config('contact.inbox'))
                ->replyTo($data['email'], $data['name'])
                ->subject('ახალი მოთხოვნა SHARK-ის ვებსაიტიდან');
        });

        return response()->json([
            'message' => 'თქვენი ინფორმაცია წარმატებით გაიგზავნა.',
        ]);
    }
}
