<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Enums\RoleEnum;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAbleTo('posts-create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return ($user->isAbleTo('posts-update') && $post->user_id == $user->id) || $user->hasRole(RoleEnum::ADMIN);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return ($user->isAbleTo('posts-delete') && $post->user_id == $user->id) || $user->hasRole(RoleEnum::ADMIN);
    }
}
