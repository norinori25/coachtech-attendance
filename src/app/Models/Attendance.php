<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'status',
        'start_time',
        'end_time',
    ];

    // リレーション: 勤怠はユーザーに属する
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // リレーション: 勤怠は複数の休憩を持つ
    public function breakRecords()
    {
        return $this->hasMany(BreakRecord::class);
    }

    // 合計勤務時間（時間:分）
    public function getTotalHoursAttribute()
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }

        // 出勤〜退勤の差分（分単位）
        $workMinutes = Carbon::parse($this->end_time)
            ->diffInMinutes(Carbon::parse($this->start_time));

        // 休憩時間を差し引き
        if ($this->breakRecords && $this->breakRecords->count()) {
            foreach ($this->breakRecords as $break) {
                if ($break->start_time && $break->end_time) {
                    $workMinutes -= Carbon::parse($break->end_time)
                        ->diffInMinutes(Carbon::parse($break->start_time));
                }
            }
        }

        // 時間:分 形式に変換
        $hours = floor($workMinutes / 60);
        $minutes = $workMinutes % 60;

        return sprintf('%2d:%02d', $hours, $minutes);
    }
}