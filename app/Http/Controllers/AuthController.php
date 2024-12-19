<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Enums\RoleEnum;
use App\Traits\ApiResponse;
use App\Helpers\GeneralHelper;


/**
 * @group Auth
 *
 * APIs for authentication.
 */
class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Register user
     *
     * @unauthenticated
     * @header Content-Type application/json

     * @bodyParam name string required The last name of the user. Example: John Doe
     * @bodyParam email string required The email of the User. Example: user@email.com
     * @bodyParam password string required The password of the User. Example: 123@!PASSWORD
     * @bodyParam password_confirmation string required The confirmation of the password. Example: 123@!PASSWORD
     *
     * @param  Request $request
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'string', Password::default(), 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => GeneralHelper::generateUsername(),
            'password' => Hash::make($request->password),
        ]);

        $user->addRole(RoleEnum::USER);

        return $this->success(
            'User Created successfully',
            $user,
            201
        );
    }

    /**
     * Login
     *
     * @unauthenticated
     * @header Content-Type application/json
     *
     * Handle a login request to the application. Below are the default users with different role generated from the seeder.
     *
     * Administrator: admin@blog.com manage all posts, users, comments, roles, and tags.
     *
     * Author: author@blog.com manage his own posts, comments and tags.
     *
     * User: user@blog.com normal user that can read posts and leave a comment.

     * @bodyParam email string required The email of the User. Example: admin@blog.com
     * @bodyParam password string required The password of the User. Example: Password.0!
     *
     * @param  Request $request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Invalid credentials', 401);
        }

        $user = $request->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success(
            'Login successful',
            [
                'user' => $user->append('role'),
                'token' => $token,
            ]
        );
    }

    /**
     * Logout
     *
     * @authenticated
     *
     * @param  Request $request
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success('Logout successful');
    }
}
