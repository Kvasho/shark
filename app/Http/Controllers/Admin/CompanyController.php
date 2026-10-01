<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Partner;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * კომპანიის გვერდი: თანამშრომლები და პარტნიორები.
     */
    public function index(): View
    {
        return view('admin.pages.company', [
            'employees' => Employee::ordered()->get(),
            'partners' => Partner::ordered()->get(),
        ]);
    }
}
