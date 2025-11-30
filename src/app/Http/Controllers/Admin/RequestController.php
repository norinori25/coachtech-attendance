<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceRequest;

class RequestController extends Controller
{
    // 申請一覧（承認待ち／承認済み）
    public function index()
    {
        $requests = AttendanceRequest::with('user', 'attendance')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.request.index', compact('requests'));
    }

    public function approveForm($attendance_correct_request_id)
    {
        $request = AttendanceRequest::with('user','attendance')
        ->findOrFail($attendance_correct_request_id);

        return view('admin.request.approve', compact('request'));
    }

    public function approve($attendance_correct_request_id)
    {
        $request = AttendanceRequest::findOrFail($attendance_correct_request_id);
        $request->update(['status' => '承認済み']);

        return redirect()->route('admin.attendance_request.index')
        ->with('message', '修正申請を承認しました。');
    }
}