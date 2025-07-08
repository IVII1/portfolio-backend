<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
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
            'owner' => 'string|required',
            'repo' => 'string|required',
            'title' => 'string|required',
            'subtitle' => 'string|required',
            'description' => 'string|required',
            'purpose' => 'string|required|in:Personal Project, Commission',
            'type' => 'string|required|in:Solo Project,Collaboration,Open Source Contribution',
            'live_url' => 'url|required',
           'challenges' => 'array|required|min:1',
           'features' => 'array|required|min:1',
           'key_takeaways' => 'array|required|min:1',
           'stack' => 'array|required|min:1',
           'stack.*' => 'string|distinct',
           'key_takeaways.*' => 'string|distinct',
           'features.*' => 'string|distinct',
           'challenges.*' => 'string|distinct',
            'gallery' => 'array|required|min:1',
            'gallery.*' => 'file|image|max:2048',

        ];
    }
}
