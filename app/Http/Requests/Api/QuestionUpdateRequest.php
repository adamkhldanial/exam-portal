<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class QuestionUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'question_text' => ['required', 'string'],
            'type' => ['required', 'in:multiple_choice,open_text'],
            'marks' => ['required', 'integer', 'min:1'],
            'order' => ['required', 'integer', 'min:1'],
        ];
    }
}
