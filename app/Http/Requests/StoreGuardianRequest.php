<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGuardianRequest extends FormRequest
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
            "name" => 'required|string|max:255',
            "e_name" => 'nullable|string|max:255',
            "phone" => 'required|string|max:25',
            "email" => ['nullable', 'email', 'max:255', Rule::unique('parents', 'email')->ignore($this->guardian)],
            "address" => 'required|string|max:255',
            "date_of_birth" => 'required|date',
            "gender" => 'required|in:male,female',
        ];
    }
}
