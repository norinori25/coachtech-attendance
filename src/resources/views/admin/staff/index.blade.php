@extends('layouts.default')

@section('title', 'スタッフ一覧（管理者）')

@section('content')
@include('components.admin_header')

<div class="container">
    <h1>スタッフ一覧（管理者）</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>勤怠一覧</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <a href="{{ url('/admin/attendance/staff/' . $user->id) }}" class="btn btn-info">
                            勤怠一覧
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">スタッフが登録されていません</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection