<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');          // 申請者
            $table->unsignedBigInteger('attendance_id');    // 対象勤怠
            $table->string('reason');                       // 修正理由（備考）
            $table->time('break_start')->nullable();        // 休憩開始（追加分）
            $table->time('break_end')->nullable();          // 休憩終了（追加分）
            $table->string('status')->default('承認待ち'); // 状態（承認待ち／承認済み）
            $table->timestamps();

            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_requests');
    }
}
