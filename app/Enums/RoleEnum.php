<?php

namespace App\Enums;

use App\Enums\EnumToArray;

enum RoleEnum: string
{
    use EnumToArray;

    case ADMIN = 'admin';

    case AUTHOR = 'author';

    case USER = 'user';
}
