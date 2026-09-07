<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
            'attendance' => 'required|array',
            'attendance.*' => 'required|array',
            "attendance.*.student_id" => 'required|exists:students,id',
            "attendance.*.status" => "required|in:present,absent,excused",

            'penalties' => 'nullable|array',
            'penalties.*' => 'required|array',
            'penalties.*.student_id' => 'required|exists:students,id',
            'penalties.*.penalty_type' => 'required|string|max:100',
            'penalties.*.reason' => 'required|string|max:1000',
            'penalties.*.notes' => 'nullable|string|max:1000',
        ];
    }
}
