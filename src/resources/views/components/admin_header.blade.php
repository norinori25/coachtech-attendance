<header class="header">
    <div class="header__logo">
        <a href="/admin"><img src="{{ asset('img/logo.svg') }}" alt="管理者ロゴ"></a>
    </div>
    <nav class="header__nav">
        <ul>
            @if(Auth::guard('admin')->check())
                <li><a href="{{ route('admin.attendance.index') }}">勤怠一覧</a></li>
                <li><a href="{{ route('admin.staff.index') }}">スタッフ一覧</a></li>
                <li><a href="{{ route('admin.attendance_request.index') }}">申請一覧</a></li>
                <li>
                    <form action="/admin/logout" method="post">
                        @csrf
                        <button class="header__logout">ログアウト</button>
                    </form>
                </li>
            @endif
        </ul>
    </nav>
</header>