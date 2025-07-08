<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
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
            'owner' => 'string',
            'repo' => 'string',
            'title' => 'string',
            'subtitle' => 'string',
            'description' => 'string',
            'purpose' => 'string',
            'type' => 'string||in:Solo Project,Collaboration,Open Source Contribution',
            'live_url' => 'url',
            'challenges' => 'array',
            'features' => 'array',
            'key_takeaways' => 'array',
            'stack' => 'array',
            'stack.*' => 'string|distinct',
            'key_takeaways.*' => 'string|distinct',
            'features.*' => 'string|distinct',
            'challenges.*' => 'string|distinct',
            'gallery' => 'array|min:1',
            'gallery.*' => 'file|image|max:2048',

        ];
    }
}
