<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalendarEntryStoreRequest extends FormRequest
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
            'date_published' => 'date|required',
            'title' => 'max:255|string|required',
            'content' => 'string|required',
            'slug' => 'string', 
            'highlighted' => 'boolean|required',
            'category_ids' => 'sometimes|array',
            'category_ids.*' => 'exists:categories,id',
            'type' => 'required|in:automated,manual'

        ];
    }
}
