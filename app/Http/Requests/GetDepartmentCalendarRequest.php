<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetDepartmentCalendarRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
           'month' => ['required', 'date_format:Y-m'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
