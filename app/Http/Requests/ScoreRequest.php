<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScoreRequest extends FormRequest
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
            'scores' => 'required|array',

            'scores.*.subjectID' => 'required|exists:subjects,id',

            'scores.*.semesters' => 'required|array',

            'scores.*.semesters.*.id' => 'required|exists:semesters,id',
            'scores.*.semesters.*.final' => 'nullable',
            'scores.*.semesters.*.final.*.id' => 'nullable|exists:student_scores,id',
            'scores.*.semesters.*.final.value' => 'nullable|numeric|min:0',

            'scores.*.semesters.*.quizzes' => 'nullable|array',
            'scores.*.semesters.*.quizzes.*.id' => 'nullable|exists:student_scores,id',
            'scores.*.semesters.*.quizzes.*.value' =>  'nullable|numeric|min:0',
        ];
    }
}
