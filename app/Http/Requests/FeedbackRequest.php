<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeedbackRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'scope' => ['required', Rule::in(['system', 'hospital', 'department'])],

            'hospital_id' => [
                'nullable',
                'required_if:scope,hospital',
                'exists:hospitals,id',
            ],

            'department_id' => [
                'nullable',
                'required_if:scope,department',
                'exists:departments,id',
            ],

            'type' => ['required', Rule::in(['complaint', 'suggestion', 'inquiry'])],

            'message' => ['required', 'string', 'min:10', 'max:5000'],

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
    
            if ($this->scope === 'system' && ($this->hospital_id || $this->department_id)) {
                $validator->errors()->add('scope', 'System feedback should not contain hospital or department.');
            }
    
            if ($this->scope === 'hospital' && $this->department_id) {
                $validator->errors()->add('department_id', 'Department is not allowed for hospital scope.');
            }
    
            if ($this->scope === 'department' && $this->hospital_id) {
                $validator->errors()->add('hospital_id', 'Hospital is not allowed for department scope.');
            }
        });
    }
    
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
