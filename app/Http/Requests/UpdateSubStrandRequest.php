<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubStrandRequest extends FormRequest
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
            'strand_id'              => 'integer|exists:strands,id',
            'name'                   => [
                'string',
                Rule::unique('sub_strands', 'name')
                    ->where('strand_id', $this->input('strand_id'))->ignore($this->route('strand'))
            ]
        ];
    }

    public function messages(): array
    {
        return [
            "strand_id.required" => "The sub strand field is required."
        ];
    }
}
