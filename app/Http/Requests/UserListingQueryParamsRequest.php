<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserListingQueryParamsRequest extends FormRequest
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
            'role' => 'nullable|string|exists:roles,name',
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
                'description' => 'Search term to filter users.',
                'example' => 'Laravel',
            ],
            'role' => [
                'description' => 'Role name to filter users.',
                'example' => 'admin',
            ]
        ];
    }
}
