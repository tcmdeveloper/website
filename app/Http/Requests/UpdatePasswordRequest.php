<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Please enter your current password.',
            'current_password.current_password' => 'The current password is incorrect.',

            'password.required' => 'Please enter a new password.',
            'password.confirmed' => 'Password confirmation does not match.',

            'password.min' => 'Your password must be at least 8 characters long.',
            'password.mixed' => 'Your password must include both uppercase and lowercase letters.',
            'password.letters' => 'Your password must include at least one letter.',
            'password.numbers' => 'Your password must include at least one number.',
            'password.symbols' => 'Your password must include at least one special character.',
            'password.uncompromised' => 'This password has appeared in a data breach. Please choose a different one.',
        ];
    }
}