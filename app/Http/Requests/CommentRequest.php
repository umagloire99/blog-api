<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends BaseFormRequest
{

    /**
     * store
     *
     * @return array
     */
    public function store(): array
    {
        return [
            'content' => 'required|string',
        ];
    }

    /**
     * update
     *
     * @return array
     */
    public function update(): array
    {
        return [
            'content' => 'required|string'
        ];
    }

    /**
     * Add bodyParameters for API documentation.
     *
     * @return array
     */
    public function bodyParameters(): array
    {
        return [
            'content' => [
                'description' => 'The content of the comment.',
                'example' => 'This is a great post!'
            ],
        ];
    }
}
