<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceRequest;

class AttendanceRequestController extends Controller
{
    public function list(Request $request)
    {
        $user = auth()->user();
        $status = $request->input('status'); // pending or approved

        $query = AttendanceRequest::where('user_id', $user->id);

        if ($status === 'pending') {
            $query->where('status', '承認待ち');
        } elseif ($status === 'approved') {
            $query->where('status', '承認済み');
        }

        $requests = $query->orderBy('created_at', 'desc')->get();

        return view('user.request.index', compact('requests'));
    }

    // 修正申請保存
    public function store(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'note' => 'required|string|max:255',
            'break_start_new' => 'nullable|date_format:H:i',
            'break_end_new' => 'nullable|date_format:H:i|after:break_start_new',
        ]);

        AttendanceRequest::create([
            'user_id' => auth()->id(),
            'attendance_id' => $request->attendance_id,
            'reason' => $request->note,
            'break_start' => $request->break_start_new,
            'break_end' => $request->break_end_new,
            'status' => '承認待ち',
        ]);

        return redirect('/stamp_correction_request/list?status=pending')
            ->with('message', '修正申請を送信しました。承認待ち一覧に表示されます。');
    }
}