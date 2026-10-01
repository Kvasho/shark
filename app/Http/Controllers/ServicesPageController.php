<?php

namespace App\Http\Controllers;

use App\Models\ServiceStep;
use Illuminate\View\View;

class ServicesPageController extends Controller
{
    public function __invoke(): View
    {
        return view('user.pages.services', [
            'steps' => ServiceStep::active()->ordered()->with('images')->has('images')->get(),
        ]);
    }
}
