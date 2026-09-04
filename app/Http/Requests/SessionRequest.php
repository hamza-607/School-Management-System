<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class SessionRequest extends FormRequest
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
        $data = [
            "subject" => ['required', Rule::when($this->subject !== 'NEW', ['exists:subjects,id'])],
            "staff" =>  ['required', Rule::when($this->staff !== 'NEW', ['exists:staff,id'])],
            "start_time" => 'required|date_format:H:i:s',
            "end_time" => 'required|date_format:H:i:s',
            "day" => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',

            //مادة جديدة
            "new_subject_name" => 'exclude_unless:subject,NEW|required|string|max:255',
            "new_subject_e_name" => 'exclude_unless:subject,NEW|nullable|string|max:255',
            "new_subject_description" => 'exclude_unless:subject,NEW|nullable|string|max:1000',

            //مدرس جديد
            "new_staff_name" => 'exclude_unless:staff,NEW|required|string|max:255',
            "new_staff_e_name" => 'exclude_unless:staff,NEW|nullable|string|max:255',
            "new_staff_img" => 'exclude_unless:staff,NEW|nullable|image|max:5120',
            "new_staff_phone" => 'exclude_unless:staff,NEW|required|string|max:20',
            "new_staff_email" => 'exclude_unless:staff,NEW|required|email|max:255|unique:staff,email',
            "new_staff_date_of_birth" => 'exclude_unless:staff,NEW|required|date',
            "new_staff_gender" => 'exclude_unless:staff,NEW|required|in:male, female',
            "new_staff_contract_file" => 'exclude_unless:staff,NEW|required|file|max:20480',
            "new_staff_salary" => 'exclude_unless:staff,NEW|required|numeric|min:0',
            "new_staff_create_account" => 'exclude_unless:staff,NEW|nullable|string|in:on,off',
            "new_staff_password" => [
                'exclude_unless:new_staff_create_account,on',
                'required',
                'confirmed',
                Password::min(6)
            ],

        ];

        return $data;
    }
}
