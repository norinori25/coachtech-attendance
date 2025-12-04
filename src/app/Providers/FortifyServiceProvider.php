<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\LoginRequest;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use Laravel\Fortify\Contracts\LogoutResponse;


class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 会員登録完了後のリダイレクト（メール認証誘導画面へ）
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request)
            {
                return redirect()->route('verification.notice');
            }
        });
    }

    public function boot(): void
    {
        // 新規登録処理
        Fortify::createUsersUsing(CreateNewUser::class);

        // 会員登録画面
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // ログイン画面（GET /login）
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // ログアウト後のリダイレクト
        $this->app->singleton(LogoutResponse::class, function ($app) {
            return new class implements LogoutResponse {
                public function toResponse($request)
                {
                    return redirect('/login');
                }
            };
        });

        // ログイン試行制限
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email.$request->ip());
        });

        // Fortify のデフォルトログインリクエスト →  LoginRequest に差し替え
        $this->app->singleton(FortifyLoginRequest::class, LoginRequest::class);
    }
}
