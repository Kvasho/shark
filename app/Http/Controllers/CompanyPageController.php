<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Partner;
use Illuminate\View\View;

class CompanyPageController extends Controller
{
    public function __invoke(): View
    {
        return view('user.pages.company', [
            'employees' => Employee::active()->ordered()->get(),
            'partners' => Partner::active()->ordered()->get(),
        ]);
    }
}
