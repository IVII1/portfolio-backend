<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalendarEntryUpdateRequest extends FormRequest
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
        return [
             'date_published' => 'date',
            'title' => 'max:255|string',
            'content' => 'string',
            'highlighted' => 'boolean',
            'category_ids' => 'sometimes|array',
            'category_ids.*' => 'exists:categories,id',
            'type' => 'in:automated,manual'
        ];
    }
}
