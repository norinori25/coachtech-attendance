<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Attendance;

class AttendanceRequest extends Model
{
    use HasFactory;

    /**
     * 一括代入可能なカラム
     */
    protected $fillable = [
        'user_id',
        'attendance_id',
        'reason',
        'break_start',
        'break_end',
        'status',
        'attendance_date',
    ];

    /**
     * 型キャスト
     */
    protected $casts = [
        'attendance_date' => 'date',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
        'break_start'     => 'datetime:H:i',
        'break_end'       => 'datetime:H:i',
    ];

    /**
     * ユーザーとのリレーション
     * 申請を出したユーザーを取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 勤怠とのリレーション
     * 申請対象の勤怠データを取得
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}