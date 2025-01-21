<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
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
        $userId = $this->route('user') ? $this->route('user')->id : null;
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $userId,
            'phone' => 'nullable|regex:/^\+?[0-9]{7,15}$/',
            'password' => [
                'nullable',
                'confirmed', 
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers(),
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'plan' => 'nullable',
            'created_quiz_count' => 'nullable|integer',
        ];
    }
}
