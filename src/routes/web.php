<?php

use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AttendanceController;
use App\Http\Controllers\User\AttendanceRequestController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::middleware(['auth','verified'])->group(function () {
    /* 出勤登録画面 */
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::post('/attendance', [AttendanceController::class, 'store']);

    /* 勤怠一覧 */
    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.index');

    /* 勤怠詳細 */
    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'show']);

    /* 勤怠修正申請一覧 */
    Route::get('/stamp_correction_request/list', [AttendanceRequestController::class, 'list'])->name('attendance_request.index');
    // 修正申請保存
    Route::post('/stamp_correction_request', [AttendanceRequestController::class, 'store'])->name('attendance_request.store');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    session()->get('unauthenticated_user')->sendEmailVerificationNotification();
    session()->put('resent', true);
    return back()->with('message', 'Verification link sent!');
})->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    session()->forget('unauthenticated_user');
    return redirect('/attendance');
})->middleware(['auth'])->name('verification.verify');

Route::prefix('admin')->group(function () {

    /* 管理者ログイン */
    Route::get('/login', [AdminAuthController::class, 'create']);
    Route::post('/login', [AdminAuthController::class, 'store']);

    /* 認証必須 */
    Route::middleware('auth:admin')->group(function () {

        /* 勤怠一覧 */
        Route::get('/attendance/list', [AdminAttendanceController::class, 'index']);

        /* 勤怠詳細 */
        Route::get('/attendance/{id}', [AdminAttendanceController::class, 'show']);

        /* スタッフ一覧 */
        Route::get('/staff/list', [StaffController::class, 'index'])->name('admin.staff.index');

        /* スタッフ別勤怠 */
        Route::get('/attendance/staff/{id}', [AdminAttendanceController::class, 'staffList']);

        /* 修正申請（管理者） */
        Route::get('/stamp_correction_request/list', [RequestController::class, 'index'])->name('admin.attendance_request.index');
    });
});

Route::middleware('auth:admin')->group(function () {
    // 承認画面表示
    Route::get('/stamp_correction_request/approve/{attendance_correct_request_id}', [RequestController::class, 'approveForm'])
    ->name('admin.attendance_request.approveForm');

    // 承認処理
    Route::post('/stamp_correction_request/approve/{attendance_correct_request_id}', [RequestController::class, 'approve'])
    ->name('admin.attendance_request.approve');
});