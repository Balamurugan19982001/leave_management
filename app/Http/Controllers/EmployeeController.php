<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required|min:3',
            'email' => 'required|email|unique:employees',
            'role' => 'required|in:manager,support,developer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $employee = Employee::create($validated);
        return response()->json($employee, 201);
    }

    public function index()
    {
        $employees = Employee::withCount('leaves')->get();
        return response()->json($employees);
    }
}
