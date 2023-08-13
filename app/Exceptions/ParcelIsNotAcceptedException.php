<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ParcelIsNotAcceptedException extends Exception
{
    public function render(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'parcel is not accepted.',
        ], Response::HTTP_FORBIDDEN);
    }
}
