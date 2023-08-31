<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubStrandRequest extends FormRequest
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
            'strand_id'              => 'required|integer|exists:strands,id',
            'name'                   => [
                'required',
                'string',
                Rule::unique('sub_strands', 'name')->where('strand_id', $this->input('strand_id')),
            ],
            'indicator'              => 'required|string',
            'highly_competent'       => 'required|string',
            'competent'              => 'required|string',
            'approaching_competence' => 'required|string',
            'needs_improvement'      => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            "strand_id.required" => "The strand field is required."
        ];
    }
}
