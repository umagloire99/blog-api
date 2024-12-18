<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserListingQueryParamsRequest;
use App\Models\Role;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;

/**
 * @group Users
 * @authenticated
 *
 * Mange Users.
 */
class UserController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the user.
     *
     * @queryParam page integer The page number for pagination. Example: 2
     * @queryParam per_page integer Number of results per page. Defaults to 10. Example: 5
     * @queryParam search string Search term to filter blog posts. Example: Laravel
     * @queryParam role string Role name. Example: admin

     */
    public function index(UserListingQueryParamsRequest $request)
    {
        $users = User::search($request->search, function ($query) use ($request) {
            $query->when($request->role, function ($query) use ($request) {
                $query->whereRole($request->role);
            });
        })->paginate()->appends(['role']);

        return $this->success(
            'successfully retrieved users',
            [
                'users' => $users,
                'options' => [
                    'roles' => Role::all()
                ]
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->success(
            'Create a user',
            ['roles' => Role::all()]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'username' => GeneralHelper::generateUsername(),
            'email' => $request->email,
            'password'=> Hash::make($request->password)
        ]);

        $user->roles()->attach($request->role_id);

        return $this->success(
            'User successfully created',
            $user,
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->success(
            'User retrieved successfully.',
            $user->append('role')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return $this->success(
            'Edit user.',
            [
                'user' => $user->append('role'),
                'roles' => Role::all()
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $user->forceFill($request->only('name', 'email'));
        $user->roles()->sync($request->role_id);

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return $this->success(
            'User updated successfully.',
            $user->append('role')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->success(
            'User deleted.'
        );
    }
}
