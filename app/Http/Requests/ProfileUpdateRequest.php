<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'min:2', 'max:100', 'regex:/^[\pL\s\-\'\.]+$/u',],
            'username' => ['required', 'string', 'min:3', 'max:30', 'regex:/^[a-zA-Z0-9_\.]+$/', Rule::unique(User::class)->ignore($this->user()->id)],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'bio' => ['nullable', 'string', 'max:500'],
            'country_code' => ['nullable', 'string', 'size:2'],
            'state_code' => ['nullable', 'string', 'size:2'],
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'name.min' => 'Your name must be at least 3 characters long.',
            'name.max' => 'Your name cannot be more that 100 characters.',
            'name.regex' => 'The name may only contain letters, spaces, hyphens, apostrophes, and periods.',

            'username.required' => 'Enter a username.',
            'username.string' => 'Username is invalid.',
            'username.min' => 'Your name must be at least 3 characters long.',
            'username.max' => 'Your name cannot be more that 30 characters.',
            'username.regex' => 'The username may only contain letters, numbers, underscores, and dots',

            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email is already in use.',
            'email.max' => 'Email address is too long',

            'bio.string' => 'Bio text is invalid.',
            'bio.max' => 'Bio can\'t be more than 500 characters.',

            'country_code.string' => 'There was a problem with the country you selected.',
            'country_code.max' => 'There was a problem with the country you selected.',

            'state_code.string' => 'There was a problem with the state you selected.',
            'state_code.max' => 'There was a problem with the state you selected.',
        ];
    }
}
