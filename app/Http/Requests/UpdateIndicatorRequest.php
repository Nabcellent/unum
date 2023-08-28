<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIndicatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        dd($this->get('indicator'));
        return [
            'name'                   => ['string', Rule::unique('indicators', 'name')->ignore($indicator->id)],
            'sub_strand_id'          => 'integer|exists:sub_strands,id',
            'highly_competent'       => 'required|string',
            'competent'              => 'required|string',
            'approaching_competence' => 'required|string',
            'needs_improvement'      => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            "sub_strand_id.required" => "The sub strand field is required."
        ];
    }
}
