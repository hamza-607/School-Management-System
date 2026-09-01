<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StaffRequest extends FormRequest
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
        // dd($this->staff_member);
        // dd($this->isMethod('PUT'));
        $data = [
            "name"   => 'required|string|max:255',
            "e_name" => 'nullable|string|max:255',
            "img" => 'nullable|image|max:5120',
            'salary' => 'required|numeric|min:0',
            "phone"  => 'required|string|max:20',
            "email"  =>  ['required', 'email', 'max:255', Rule::unique('staff', 'email')->ignore($this->staff_member)],
            "date_of_birth" => 'required|date',
            "gender" => 'required|in:male, female',
            "staff_type" => 'required|in:teacher,admin,other',
            'user_id' => 'nullable|exists:users,id',
            "new_staff_type" => 'exclude_unless:staff_type,other|required|string|max:255',
            "subject" => ['exclude_unless:staff_type,teacher', 'required', Rule::when($this->subject !== 'NEW', ['exists:subjects,id'])],

            "create_account" => 'nullable|in:on, off',
            "password" => [
                'exclude_unless:create_account,on',
                'required',
                'confirmed',
                Password::min(6)
            ],

            //مادة جديدة
            "new_subject_name" => "exclude_unless:subject,NEW|required|string|max:255",
            "new_subject_e_name" => "exclude_unless:subject,NEW|nullable|string|max:255",
            "new_subject_description" => "exclude_unless:subject,NEW|nullable|string|max:1000"
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $data['contract_file'] = 'nullable|file|max:20480';
        } else {
            $data['contract_file'] = 'required|file|max:20480';
        }

        return $data;
    }
}
