<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.current_password' => 'მიმდინარე პაროლი არასწორია.',
            'password.different' => 'ახალი პაროლი უნდა განსხვავდებოდეს მიმდინარისგან.',
            'password.confirmed' => 'პაროლები ერთმანეთს არ ემთხვევა.',
            'password.min' => 'პაროლი უნდა შეიცავდეს მინიმუმ :min სიმბოლოს.',
            'required' => ':attribute სავალდებულოა.',
        ], [
            'current_password' => 'მიმდინარე პაროლი',
            'password' => 'ახალი პაროლი',
        ]);

        $request->user()->update(['password' => $validated['password']]);

        $request->session()->regenerate();

        return back()->with('status', 'პაროლი წარმატებით შეიცვალა.');
    }
}
