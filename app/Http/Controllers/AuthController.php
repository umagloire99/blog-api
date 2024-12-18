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
