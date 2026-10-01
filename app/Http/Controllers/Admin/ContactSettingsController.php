<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactSettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.pages.contact', [
            'phone' => Setting::valueOf('contact.phone'),
            'email' => Setting::valueOf('contact.email'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            // ციფრები, +, ინტერვალი, ტირე და ფრჩხილები: "+995 32 200 00 00", "(032) 200-00-00".
            'phone' => ['required', 'string', 'max:30', 'regex:/^\+?[0-9\s\-()]{6,30}$/'],
            'email' => ['required', 'email:rfc', 'max:150'],
        ], [
            'phone.required' => 'ტელეფონის ნომერი სავალდებულოა.',
            'phone.regex' => 'ნომერი შეიძლება შეიცავდეს მხოლოდ ციფრებს, +, ინტერვალს, ტირეს და ფრჩხილებს.',
            'phone.max' => 'ნომერი ძალიან გრძელია.',
            'email.required' => 'ელფოსტა სავალდებულოა.',
            'email.email' => 'ელფოსტის მისამართი არასწორია.',
            'email.max' => 'ელფოსტა ძალიან გრძელია.',
        ]);

        Setting::put([
            'contact.phone' => trim(preg_replace('/\s+/', ' ', $data['phone'])),
            'contact.email' => mb_strtolower(trim($data['email'])),
        ]);

        return redirect()
            ->route('admin.contact')
            ->with('success', 'საკონტაქტო ინფორმაცია განახლდა.');
    }
}
