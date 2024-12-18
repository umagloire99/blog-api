<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostListingQueryParamsRequest extends FormRequest
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
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
            'search' => 'nullable|string',
            'tags' => 'nullable|array|exists:tags,slug',
        ];
    }

    /**
     * Handle the query parameters and body parameters together.
     */
    protected function prepareForValidation()
    {
        $this->merge($this->query());
    }

    /**
     * bodyParameters
     *
     */
    public function bodyParameters()
    {
        return [
            'page' => [
                'description' => 'The page number for pagination.',
                'example' => 2,
            ],
            'per_page' => [
                'description' => 'Number of results per page. Defaults to 10.',
                'example' => 5,
            ],
            'search' => [
                'description' => 'Search term to filter blog posts.',
                'example' => 'Laravel',
            ],
            'tags' => [
                'description' => 'Filter blog posts based on tags',
                'example' => ['tag-1', 'tag-2', 'tag-3'],
            ]
        ];
    }
}
