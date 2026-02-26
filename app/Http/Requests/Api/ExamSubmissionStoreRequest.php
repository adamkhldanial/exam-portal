<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ExamSubmissionStoreRequest extends FormRequest
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
            'exam_id' => ['required', 'integer', 'exists:exams,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'started_at' => ['required', 'date'],
            'submitted_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'score' => ['nullable', 'integer', 'min:0'],
            'total_marks' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'in:in_progress,submitted,graded'],
        ];
    }
}
