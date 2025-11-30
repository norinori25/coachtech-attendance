@extends('layouts.default')

@section('title', 'スタッフ別勤怠一覧（管理者）')

@section('content')
@include('components.admin_header')

<div class="container">
    <h1>{{ $user->name }} さんの勤怠一覧</h1>

    {{-- 月切替 --}}
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ url('/admin/attendance/staff/' . $user->id . '?month=' . \Carbon\Carbon::parse($month)->subMonth()->format('Y-m')) }}" 
           class="btn btn-outline-primary">前月</a>
        <span class="h5">{{ $month }}</span>
        <a href="{{ url('/admin/attendance/staff/' . $user->id . '?month=' . \Carbon\Carbon::parse($month)->addMonth()->format('Y-m')) }}" 
           class="btn btn-outline-primary">翌月</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>日付</th>
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
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->start_time ?? '' }}</td>
                    <td>{{ $attendance->end_time ?? '' }}</td>
                    <td>
                        @if($attendance->breakRecords && $attendance->breakRecords->count())
                            @foreach($attendance->breakRecords as $break)
                                {{ $break->start_time }} - {{ $break->end_time }}<br>
                            @endforeach
                        @else
                            なし
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

    <div class="mt-3">
        <a href="{{ url('/admin/staff/list') }}" class="btn btn-secondary">スタッフ一覧に戻る</a>
    </div>
</div>
@endsection