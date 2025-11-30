<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'break_start',
        'break_end',
    ];

    // リレーション: 休憩は勤怠に属する
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
