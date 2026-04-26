<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'phone' => [
                'nullable',
                'string',
                Rule::unique('users', 'phone')->ignore(auth()->id()),
            ],

            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore(auth()->id()),
            ],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'phone.unique' => 'This phone number is already taken.',
            'email.unique' => 'This email is already taken.',
            'email.email' => 'Invalid email format.',
        ];
    }
}
