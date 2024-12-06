<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    public function store(Request $request, $employeeId)
    {
        try {
            $validator = Validator::make($request->all(), [ 
                'leave_type' => 'required',
                'start_date' => 'required|date|before:end_date',
                'end_date' => 'required|date|after:start_date',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

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

        }catch (Exception $e) {
            return response()->json($e, 201);
            return response()->json(['error' => $e], 422);
        }
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

        $validator = Validator::make($request->all(), [ 
            'status' => 'required|in:Approved,Rejected',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $leave->update($request->all());
        return response()->json($leave);
    }
}
