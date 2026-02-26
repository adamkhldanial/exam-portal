<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ExamSubmissionUpdateRequest extends FormRequest
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
            'submitted_at' => ['nullable', 'date'],
            'score' => ['nullable', 'integer', 'min:0'],
            'total_marks' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:in_progress,submitted,graded'],
        ];
    }
}
