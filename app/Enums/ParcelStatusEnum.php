<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum ParcelStatusEnum: string
{
    use EnumToArray;

    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case CANCELED = 'canceled';

    case IS_GOING_TO_SOURCE_LOCATION = 'is_going_to_source';
    case IS_GOING_TO_DESTINATION_LOCATION = 'is_going_to_destination';
    case DONE = 'done';
}
