<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends BaseFormRequest
{
    /**
     * Validation rules for creating a user.
     *
     * @return array
     */
    public function store(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|unique:users,email',
            'password' => ['required', 'string', Password::default()],
            'role_id' => 'required|exists:roles,id'
        ];
    }

    /**
     * Validation rules for updating a user.
     *
     * @return array
     */
    public function update(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' =>  ['required', 'string', Rule::unique('users')->ignore($this->route('user')?->id)],
            'role_id' => 'required|exists:roles,id',
            'password' => ['sometimes', 'string', Password::default()],
        ];
    }

    /**
     * Define body parameters for API documentation.
     *
     * @return array
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'The full name of the user.',
                'example' => 'John Doe'
            ],
            'email' => [
                'description' => 'The email address of the user. Must be unique.',
                'example' => 'johndoe@example.com'
            ],
            'password' => [
                'description' => 'The password for the user.',
                'example' => '$NoPass123!'
            ],
            'role_id' => [
                'description' => 'The ID of the role assigned to the user. Must exist in the roles table.',
                'example' => 1
            ],
        ];
    }
}
