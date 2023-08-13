<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ParcelIsNotOnGoingToDestinationStatusException extends Exception
{
    public function render(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'parcel is not on going to destination.',
        ], Response::HTTP_FORBIDDEN);
    }
}
