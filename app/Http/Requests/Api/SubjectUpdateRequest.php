<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectUpdateRequest extends FormRequest
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
        $subject = $this->route('subject');
        $subjectId = is_object($subject) ? $subject->id : $subject;

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('subjects', 'code')->ignore($subjectId)],
            'description' => ['nullable', 'string'],
            'class_room_id' => ['required', 'integer', 'exists:class_rooms,id'],
        ];
    }
}
