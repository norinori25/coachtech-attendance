@extends('layouts.default')

@section('title', '勤怠詳細（管理者）')

@section('content')

@include('components.header')
<div class="container">
    <h1>勤怠詳細</h1>

    <table class="table">
        <tr>
            <th>名前</th>
            <td>{{ $attendance->user->name }}</td>
        </tr>
        <tr>
            <th>日付</th>
            <td>{{ $attendance->date }}</td>
        </tr>
        <tr>
            <th>出勤・退勤</th>
            <td>{{ $attendance->start_time ?? '' }}</td>
            <td>{{ $attendance->end_time ?? '' }}</td>
        </tr>
        <tr>
            <th>休憩</th>
            <td>
                <ul>
                    @foreach($attendance->breakRecords as $index => $break)
                        <li>
                            @if($loop->first)
                                休憩：{{ $break->break_start }} ～ {{ $break->break_end ?? '未終了' }}
                            @else
                                休憩{{ $index+1 }}：{{ $break->break_start }} ～ {{ $break->break_end ?? '未終了' }}
                            @endif
                        </li>
                    @endforeach
                </ul>

                {{-- 追加の入力フィールド（修正申請用） --}}
                @if($attendance->status !== '承認待ち')
                    <div class="mt-2">
                        <label>休憩追加</label>
                        <input type="time" name="break_start_new" class="form-control mb-2">
                        <input type="time" name="break_end_new" class="form-control">
                    </div>
                @endif
            </td>
        </tr>
        <tr>
            <th>備考</th>
            <td>
                @if($attendance->status !== '承認待ち')
                    <input type="text" name="note" class="form-control">
                @endif
            </td>
        </tr>
    </table>

    <form action="{{ url('/stamp_correction_request') }}" method="POST">
        @csrf
        <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
        <button type="submit" class="btn btn-primary">修正</button>
    </form>
</div>
@endsection