<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\QuestionType;
use Illuminate\Validation\Rule;

class QuizRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'is_public' => 'required',
            'start_time' => 'required|date|before:end_time',
            'end_time' => 'required|date|after:start_time',
            'timer' => 'required|integer|min:1',
            'total_question' => 'required|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.question_difficulty' => 'required|string',
            'questions.*.marks' => 'required|integer|min:1',
            'questions.*.type' => 'required',
            'questions.*.total_options' => 'nullable|integer|required_if:questions.*.type,radio,checkbox|min:1',
            'questions.*.options' => 'nullable|array|required_if:questions.*.type,radio,checkbox',
            'questions.*.options.*.option' => 'nullable|string',
            'questions.*.correct_option' => 'nullable|array|required_if:questions.*.type,radio,checkbox',
        ];
    }
}
