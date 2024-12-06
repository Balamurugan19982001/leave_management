<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:employees',
            'role' => 'required|in:manager,support,developer',
        ]);

        $employee = Employee::create($validated);
        return response()->json($employee, 201);
    }

    public function index()
    {
        $employees = Employee::withCount('leaves')->get();
        return response()->json($employees);
    }
}
