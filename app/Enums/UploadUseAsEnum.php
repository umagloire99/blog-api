<?php

namespace App\Enums;

enum UploadUseAsEnum: string
{
    use EnumToArray;

    case IMAGE = 'image';

    case GALLERY = 'gallery';
}
