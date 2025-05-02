<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function index()
    {
        return Inertia::render('Employee/List', [
            'employeesIndexUrl' => route('employee.table.list'),
            'employeeShowUrl' => route('employee.show'),
            'employeeDeleteUrl' => route('employee.destroy'),
            'csrfToken' => csrf_token(),
        ]);
    }

    public function show()
    {
        return Inertia::render('Employee/Show');
    }
    public function destroy()
    {
        return Inertia::render('Employee/Delete');
    }
}
