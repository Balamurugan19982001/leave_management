<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Employee;

class LeaveTest extends TestCase
{
    public function test_leave_application()
    {
        $employee = Employee::factory()->create();

        $response = $this->postJson("/api/employees/{$employee->id}/leaves", [
            'leave_type' => 'Sick Leave',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(3)->toDateString(),
        ]);

        $response->assertStatus(201)
                 ->assertJson(['status' => 'Pending']);
    }
}
