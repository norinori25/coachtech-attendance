<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class AttendanceCorrectionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // 全ユーザーが利用可能
    }

    public function rules()
    {
        return [
            'start_time_new'  => ['nullable', 'date_format:H:i'],
            'end_time_new'    => ['nullable', 'date_format:H:i'],
            'break_start_new' => ['nullable', 'date_format:H:i'],
            'break_end_new'   => ['nullable', 'date_format:H:i'],
            'note'            => ['required', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $start      = $this->input('start_time_new');
            $end        = $this->input('end_time_new');
            $breakStart = $this->input('break_start_new');
            $breakEnd   = $this->input('break_end_new');

            // 出勤・退勤チェック
            if ($start && $end && Carbon::parse($start)->gt(Carbon::parse($end))) {
                $validator->errors()->add('start_time_new', '出勤時間もしくは退勤時間が不適切な値です');
            }
            if ($end && $start && Carbon::parse($end)->lt(Carbon::parse($start))) {
                $validator->errors()->add('end_time_new', '出勤時間もしくは退勤時間が不適切な値です');
            }

            // 休憩開始チェック
            if ($breakStart && $start && Carbon::parse($breakStart)->lt(Carbon::parse($start))) {
                $validator->errors()->add('break_start_new', '休憩時間が不適切な値です');
            }
            if ($breakStart && $end && Carbon::parse($breakStart)->gt(Carbon::parse($end))) {
                $validator->errors()->add('break_start_new', '休憩時間が不適切な値です');
            }

            // 休憩終了チェック
            if ($breakEnd && $end && Carbon::parse($breakEnd)->gt(Carbon::parse($end))) {
                $validator->errors()->add('break_end_new', '休憩時間もしくは退勤時間が不適切な値です');
            }
            if ($breakEnd && $breakStart && Carbon::parse($breakEnd)->lt(Carbon::parse($breakStart))) {
                $validator->errors()->add('break_end_new', '休憩時間が不適切な値です');
            }
        });
    }

    public function messages()
    {
        return [
            'note.required' => '備考を記入してください',
        ];
    }
}