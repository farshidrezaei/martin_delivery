<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum ParcelLocationTypeEnum: string
{
    use EnumToArray;

    case SOURCE = 'source';
    case DESTINATION = 'destination';

}
