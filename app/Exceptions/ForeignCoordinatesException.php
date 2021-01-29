<?php

namespace App\Exceptions;

use Illuminate\Contracts\Support\Responsable;

class ForeignCoordinatesException extends \RuntimeException implements Responsable
{
    public function toResponse($request)
    {
    	return response()->json([
        	'success' => 0,
        	'failed' => 1,
        	'message' => sprintf("The coordinates does not belong to user's country: %s", $request->user()->country_code),
        	'data' => [],
        ], 422);
    }
}
