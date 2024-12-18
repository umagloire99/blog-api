<?php

namespace App\Enums;

enum UploadTypeEnum: string
{
    use EnumToArray;

    case IMAGE = 'image';

    case VIDEO = 'video';
}
