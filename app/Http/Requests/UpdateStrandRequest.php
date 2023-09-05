<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStrandRequest extends FormRequest
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
            'name'             => [
                'string',
                Rule::unique('strands', 'name')
                    ->where('learning_area_id', $this->integer('learning_area_id'))->ignore($this->route('strand'))
            ],
            'learning_area_id' => 'integer|exists:learning_areas,id'
        ];
    }
}
