<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function store(EmployeeRequest $request): RedirectResponse
    {
        Employee::create([
            ...$request->employeeData(),
            'photo' => $request->file('photo')->store('employees', 'public'),
        ]);

        return redirect()
            ->route('admin.company')
            ->with('success', 'თანამშრომელი წარმატებით დაემატა.');
    }

    public function edit(Employee $employee): View
    {
        return view('admin.pages.employees.edit', compact('employee'));
    }

    public function update(EmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $data = $request->employeeData();

        if ($request->hasFile('photo')) {
            $oldPhoto = $employee->photo;
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($data);

        if (isset($oldPhoto)) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return redirect()
            ->route('admin.company')
            ->with('success', 'თანამშრომლის ინფორმაცია განახლდა.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        Storage::disk('public')->delete($employee->photo);

        return redirect()
            ->route('admin.company')
            ->with('success', 'თანამშრომელი წაიშალა.');
    }
}
