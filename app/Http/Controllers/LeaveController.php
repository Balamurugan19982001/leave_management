<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Employee;

class LeaveController extends Controller
{
    public function store(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'leave_type' => 'required',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $employee = Employee::findOrFail($employeeId);

        // Check overlapping leaves
        if (Leave::where('employee_id', $employeeId)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']]);
            })->exists()) {
            return response()->json(['error' => 'Overlapping leave dates'], 422);
        }

        $leave = $employee->leaves()->create($validated);
        return response()->json($leave, 201);
    }

    public function index($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $leaves = $employee->leaves;
        return response()->json($leaves);
    }

    public function update(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $leave->update($validated);
        return response()->json($leave);
    }
}
