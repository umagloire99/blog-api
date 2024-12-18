<?php

namespace App\Http\Requests;

class PostRequest extends BaseFormRequest
{

    /**
     * store
     *
     */
    public function store()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|array|exists:tags,id',
            'image' =>  'nullable|file|mimes:png,jpg,jpeg|max:5120',
            'gallery' => 'nullable|array',
            'gallery.*' => 'file|mimes:jpg,jpeg,png,mp4,avi|max:10240',
        ];
    }

    /**
     * store
     *
     */
    public function update()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|array|exists:tags,id',
            'image' =>  'nullable|file|mimes:png,jpg,jpeg|max:5120',
            'gallery' => 'nullable|array',
            'gallery.*' => 'file|mimes:jpg,jpeg,png,mp4,avi|max:10240',
        ];
    }

    /**
     * Get the body parameters for API documentation.
     *
     * @return array
     */
    public function bodyParameters()
    {
        return [
            'title' => [
                'description' => 'The title of the post.',
                'example' => 'A Sample Blog Post',
            ],
            'content' => [
                'description' => 'The  content of the post.',
                'example' => 'This is the body of the blog post.',
            ],
            'tags' => [
                'description' => 'An array of tag IDs associated with the post.',
                'example' => [1, 2, 3],
            ],
            'image' => [
                'description' => 'A single image file to be used as the main image for the post.',
                'example' => null,
            ],
            'gallery' => [
                'description' => 'An array of image or video files for the post gallery.',
                'example' => null,
            ],
            'gallery.*' => [
                'description' => 'Each file in the gallery. Allowed formats are images (jpg, png) or videos (mp4, avi, mov).',
                'example' => null,
            ],
        ];
    }
}
