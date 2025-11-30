@extends('layouts.default')

@section('title', '勤怠一覧（管理者）')

@section('content')
@include('components.admin_header')

<div class="container">
    <h1>勤怠一覧（管理者）</h1>

    {{-- 日付切替 --}}
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ url('/admin/attendance/list?date=' . \Carbon\Carbon::parse($date)->subDay()->toDateString()) }}" 
           class="btn btn-outline-primary">前日</a>
        <span class="h5">{{ $date }}</span>
        <a href="{{ url('/admin/attendance/list?date=' . \Carbon\Carbon::parse($date)->addDay()->toDateString()) }}" 
           class="btn btn-outline-primary">翌日</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>名前</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ $attendance->start_time ?? '' }}</td>
                    <td>{{ $attendance->end_time ?? '' }}</td>
                    <td>
                        {{-- 休憩は複数ある場合を考慮 --}}
                        @if($attendance->breaks && $attendance->breaks->count())
                            @foreach($attendance->breaks as $break)
                                {{ $break->start_time }} - {{ $break->end_time }}<br>
                            @endforeach
                        @endif
                    </td>
                    <td>{{ $attendance->total_hours ?? '' }}</td>
                    <td>
                        <a href="{{ url('/admin/attendance/' . $attendance->id) }}" class="btn btn-info">詳細</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">勤怠情報がありません</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection