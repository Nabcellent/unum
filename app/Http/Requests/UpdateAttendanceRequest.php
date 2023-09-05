<?php

namespace App\Http\Requests;

use App\Enums\Level;
use App\Settings\TermSetting;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $maxDays = app(TermSetting::class)->cat_days;

        $primary = $this->route('grade')->level === Level::PRIMARY ? "pri_" : "";

        return [
            'attendances.*.id'          => "nullable|exists:{$primary}cumulative_results",
            'attendances.*.student_id'  => "required|exists:students,id",
            'attendances.*.days_absent' => "nullable|integer|min:0|max:$maxDays",
            'exam_id'                   => "required|exists:exams,id",
        ];
    }
}
