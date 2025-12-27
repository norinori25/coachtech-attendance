<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AttendanceRequest;
use App\Models\User;
use App\Models\Attendance;

class AttendanceRequestFactory extends Factory
{
    protected $model = AttendanceRequest::class;

    public function definition()
    {
        return [
            'user_id'         => User::factory(),
            'attendance_id'   => Attendance::factory(),
            'attendance_date' => now()->toDateString(),
            'reason'          => $this->faker->sentence(),
            'break_start'     => '12:00',
            'break_end'       => '13:00',
            'status'          => '承認待ち',
        ];
    }
}