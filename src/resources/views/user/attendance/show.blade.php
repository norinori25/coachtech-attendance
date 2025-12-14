@extends('layouts.default')

@section('title', '勤怠詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/attendance_detail.css') }}">
@endsection

@section('content')

@if(auth()->user()->is_admin)
    @include('components.admin_header')
@else
    @include('components.header')
@endif

<div class="container">

    <h1 class="attendance-title">
        <span class="attendance-title__line"></span>
        勤怠詳細
    </h1>

    {{-- 申請が存在する場合 --}}
    @if($attendanceRequest)

        <table class="table attendance-detail-table">

            <tr>
                <th>名前</th>
                <td colspan="3">{{ $attendanceRequest->user->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td class="year-cell">{{ optional($attendanceRequest->attendance_date)->format('Y年') }}</td>
                <td></td>
                <td class="monthday-cell">{{ optional($attendanceRequest->attendance_date)->format('m月d日') }}</td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>{{ optional($attendanceRequest->attendance->start_time)->format('H:i') }}</td>
                <td>～</td>
                <td>{{ optional($attendanceRequest->attendance->end_time)->format('H:i') }}</td>
            </tr>
            @foreach($attendanceRequest->attendance->breakRecords as $index => $break)
                <tr>
                    <th>休憩{{ $index+1 }}</th>
                    <td>{{ optional($break->break_start)->format('H:i') }}</td>
                    <td>～</td>
                    <td>{{ optional($break->break_end)->format('H:i') ?? '' }}</td>
                </tr>
            @endforeach

            <tr>
                <th>備考</th>
                <td colspan="3">{{ $attendanceRequest->attendance->note ?? '' }}</td>
            </tr>
        </table>

        {{-- 一般ユーザーにだけステータスを表示 --}}
        @if(!auth()->user()->is_admin)
            @if($attendanceRequest->status === '承認待ち')
                <div class="text-warning">
                    ＊承認待ちのため修正はできません。
                </div>
            @elseif($attendanceRequest->status === '承認済み')
                <div class="text-success">
                    ＊この勤怠修正は承認済みです。
                </div>
            @endif
        @endif

        {{-- 管理者用承認ボタン --}}
        @if(auth()->user()->is_admin)
            @if($attendanceRequest->status === '承認待ち')
                <form action="{{ route('admin.attendance_request.approve', $attendanceRequest->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-dark">承認</button>
                </form>
            @elseif($attendanceRequest->status === '承認済み')
                <button type="button" class="btn btn-secondary" disabled>承認済み</button>
            @endif
        @endif

    {{-- 申請が存在しない場合（未申請） --}}
    @else
        <form action="{{ route('attendance_request.store') }}" method="POST" novalidate>
            @csrf
            <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">

            <table class="table attendance-detail-table">
                <tr>
                    <th>名前</th>
                    <td colspan="4">{{ $attendance->user->name }}</td>
                </tr>
                <tr>
                    <th>日付</th>
                    <td class="year-cell">{{ optional($attendance->date)->format('Y年') }}</td>
                    <td class="monthday-cell">{{ optional($attendance->date)->format('m月d日') }}</td>
                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    <td>
                        <input type="time" name="start_time_new"
                               value="{{ old('start_time_new', optional($attendance->start_time)->format('H:i')) }}">
                        @error('start_time_new')
                            <div class="form__error">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>～</td>
                    <td>
                        <input type="time" name="end_time_new"
                               value="{{ old('end_time_new', optional($attendance->end_time)->format('H:i')) }}">
                        @error('end_time_new')
                            <div class="form__error">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <th>休憩</th>
                    <td>
                        <input type="time" name="break_start_new" value="{{ old('break_start_new') }}">
                        @error('break_start_new')
                            <div class="form__error">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>～</td>
                    <td>
                        <input type="time" name="break_end_new" value="{{ old('break_end_new') }}">
                        @error('break_end_new')
                            <div class="form__error">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <th>休憩{{ $attendance->breakRecords->count() + 1 }}</th>
                    <td>
                        <input type="time" name="break_start_new" value="{{ old('break_start_new') }}">
                    </td>
                    <td>～</td>
                    <td>
                        <input type="time" name="break_end_new" value="{{ old('break_end_new') }}">
                    </td>
                </tr>
                <tr>
                    <th>備考</th>
                    <td colspan="3">
                        <textarea name="note" class="note-field" required>{{ old('note', $attendance->note ?? '') }}</textarea>
                        @error('note')
                            <div class="form__error">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
            </table>

            <div class="text-end mt-2">
                @if(auth()->user()->is_admin)
                    {{-- 管理者の場合 --}}
                    @if($attendanceRequest)
                        @if($attendanceRequest->status === '承認待ち')
                            <form action="{{ route('admin.attendance_request.approve', $attendanceRequest->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-dark">承認</button>
                            </form>
                        @elseif($attendanceRequest->status === '承認済み')
                            <button type="button" class="btn btn-secondary" disabled>承認済み</button>
                        @else
                        {{-- 承認待ち／承認済み以外なら修正ボタン --}}
                            <button type="submit" class="btn btn-dark">修正</button>
                        @endif
                    @else
                        {{-- 未申請なら修正ボタン --}}
                        <button type="submit" class="btn btn-dark">修正</button>
                    @endif
                @else
                {{-- 一般ユーザーの場合 --}}
                    <button type="submit" class="btn btn-dark">修正</button>
                @endif
            </div>
        </form>
    @endif
</div>

@endsection