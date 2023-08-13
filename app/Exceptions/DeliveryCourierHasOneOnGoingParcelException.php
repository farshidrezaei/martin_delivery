<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class DeliveryCourierHasOneOnGoingParcelException extends Exception
{
    public function render(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'parcel is not cancelable.',
        ], Response::HTTP_FORBIDDEN);
    }
}
