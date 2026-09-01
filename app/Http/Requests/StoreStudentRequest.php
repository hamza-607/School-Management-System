<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
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
        // dd($this->input('new_parent.0.id'));
        $rules = [
            "name" => "required|string|max:255",
            "e_name" => "nullable|string|max:255",
            'img' => 'nullable|image|max:5120',
            'is_active' => 'nullable|boolean',
            "grade" => ["required", Rule::when($this->grade !== "NEW", ['exists:grades,id'])],
            "section" => ["nullable", Rule::when($this->section !== "NEW", ['exists:sections,id'])],
            "date_of_birth" => "required|date",
            "gender" => "required|in:male,female",
            "phone" => "nullable|string|max:25",
            "address" => "required|string|max:255",

            "new_grade_name" => 'exclude_unless:grade,NEW|required|string|max:255',
            "new_section_name" => 'exclude_unless:section,NEW|required|string|max:255',
            "new_section_capacity" => 'exclude_unless:section,NEW|nullable',

            "old_parent" => 'nullable|array',
            // "old_parent.relationship_to_student" => 'exclude_if:old_parent,null|required|string|max:50',
            "old_parent.relationship_to_student" => [
                Rule::requiredIf(fn() => !is_null($this->old_parent)),
                'string',
                'max:50'
            ],

            "old_parent_ids" => 'nullable|array',

            "new_parent" => 'exclude_unless:old_parent,null|required|array',

            "new_parent.*.id" => 'nullable|exists:parents,id',
            "new_parent.*.name" => 'exclude_if:new_parent,null|required|string|max:255',
            "new_parent.*.e_name" => 'exclude_if:new_parent,null|nullable|string|max:255',
            "new_parent.*.relationship_to_student" => 'exclude_if:new_parent,null|required|string|max:50',
            "new_parent.*.phone" => 'exclude_if:new_parent,null|required|string|max:25',
            "new_parent.*.address" => 'exclude_if:new_parent,null|required|string|max:255',
            "new_parent.*.date_of_birth" => 'exclude_if:new_parent,null|required|date',
            "new_parent.*.gender" => 'exclude_if:new_parent,null|required|in:male,female',
        ];

        // dd($this->input('new_parent'));

        if ($this->input('new_parent')) {
            foreach ($this->input('new_parent') as $index => $parent) {
                $rules["new_parent.$index.email"] = [
                    'exclude_if:new_parent,null',
                    'nullable',
                    'email',
                    'max:255',
                    Rule::unique('parents', 'email')->ignore($parent['id'] ?? null),
                ];
            }
        }

        return $rules;
    }
}
