<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceController extends Controller
{

    public function index(Request $request)
    {
        // 日付指定（なければ今日）
        $date = $request->input('date', now()->toDateString());
        $currentDate = Carbon::parse($date);

        $attendances = Attendance::with(['user', 'breakRecords'])
            ->whereDate('date', $currentDate)
            ->orderBy('user_id')
            ->get();

        return view('admin.attendance.index', compact(
            'attendances',
            'currentDate'
        ));
    }


    /**
     * 勤怠詳細（1件）
     */
    public function show($id)
    {
        $attendance = Attendance::with([
            'user',
            'breakRecords',
            'attendanceRequest', // ← 修正申請
        ])->findOrFail($id);

        // 申請がなければ null
        $attendanceRequest = $attendance->attendanceRequest ?? null;

        return view('user.attendance.show', compact(
            'attendance',
            'attendanceRequest'
        ));
    }


    /**
     * スタッフ別勤怠一覧（月次）
     */
    public function staffList($id, Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $currentMonth = Carbon::parse($month . '-01');

        $user = User::findOrFail($id);

        $attendances = Attendance::with('breakRecords')
            ->where('user_id', $id)
            ->whereYear('date', $currentMonth->year)
            ->whereMonth('date', $currentMonth->month)
            ->orderBy('date')
            ->get();

        return view(
            'admin.staff.attendance',
            compact('user', 'attendances', 'currentMonth')
        );
    }
}
