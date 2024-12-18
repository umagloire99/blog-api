<?php

namespace App\Helpers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class GeneralHelper
{
    /**
     * Generate a username
     *
     * @param  ?string $email
     * @return str
     */
    public static function generateUsername(?string $username = null): string
    {
        if ($username) {
            if (User::whereUsername($username)->exists()) {
                $username = $username . time();
            }
        } else {
            $username =  uniqid('u');
        }

        return $username;
    }
}
