<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertPriResultRequest extends FormRequest
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
        return [
            "marks.*.id"         => "integer",
            "marks.*.mark"       => "required|integer|max:99",
            "marks.*.student_id" => "required|exists:students,id",
            "exam_id"            => "required|exists:exams,id",
            "sub_strand_id"      => "required|exists:sub_strands,id",
            "grade_id"           => "required|exists:grades,id",
        ];
    }
}
