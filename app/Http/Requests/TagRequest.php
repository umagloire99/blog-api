<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class TagRequest extends BaseFormRequest
{
    /**
     * Validation rules for storing a tag.
     *
     * @return array
     */
    public function store(): array
    {
        return [
            'name' => ['required', 'string', 'unique:tags,name']
        ];
    }

    /**
     * Validation rules for updating a tag.
     *
     * @return array
     */
    public function update(): array
    {
        return [
            'name' => ['required', 'string', Rule::unique('tags')->ignore($this->route('tag')?->id)]
        ];
    }

    /**
     * Body parameters for API documentation.
     *
     * @return array
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'The unique name of the tag.',
                'example' => 'Laravel'
            ],
        ];
    }
}
