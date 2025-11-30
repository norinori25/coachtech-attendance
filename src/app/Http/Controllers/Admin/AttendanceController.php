<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;

class AttendanceController extends Controller
{
    /**
     * 勤怠一覧（全ユーザーの日次勤怠）
     */
    public function index(Request $request)
    {
        // 日付指定（なければ今日）
        $date = $request->input('date', now()->toDateString());

        // 全ユーザーの勤怠を取得
        $attendances = Attendance::with('user')
            ->whereDate('date', $date)
            ->orderBy('user_id')
            ->get();

        return view('admin.attendance.index', compact('attendances', 'date'));
    }

    /**
     * 勤怠詳細（1件）
     */
    public function show($id)
    {
        $attendance = Attendance::with('user')->findOrFail($id);

        return view('admin.attendance.show', compact('attendance'));
    }

    /**
     * スタッフ別勤怠一覧（月次）
     */
    public function staffList($id, Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $user = User::findOrFail($id);

        $attendances = Attendance::where('user_id', $id)
            ->where('date', 'like', $month.'%')
            ->orderBy('date')
            ->get();

        return view('admin.staff.attendance', compact('user', 'attendances', 'month'));
    }
}