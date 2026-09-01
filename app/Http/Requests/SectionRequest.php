<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SectionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => 'required|string|max:225',
            "grade" => ["required", Rule::when($this->grade !== "NEW", ['exists:grades,id'])],
            "capacity" => 'nullable|numeric|min:30',
            "new_grade_name" => 'exclude_unless:grade,NEW|required|string|max:255',
        ];
    }
}
