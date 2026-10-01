<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;

class ContactPageController extends Controller
{
    public function __invoke(): View
    {
        $phone = Setting::valueOf('contact.phone');

        return view('user.pages.contact', [
            'phone' => $phone,
            // tel: ბმულისთვის მხოლოდ + და ციფრები: "+995 32 200 00 00" → "+995322000000".
            'phoneLink' => $phone ? preg_replace('/[^0-9+]/', '', $phone) : null,
            'email' => Setting::valueOf('contact.email'),
        ]);
    }
}
