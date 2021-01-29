<?php

namespace App\Exceptions;

use Illuminate\Contracts\Support\Responsable;

class MaxLocationException extends \RuntimeException implements Responsable
{
    public function toResponse($request)
    {
        return response()->json([
        	'success' => 0,
        	'failed' => 1,
        	'message' => sprintf('Cannot create location more than %d locations', config('shepper.max_location_user')),
        	'data' => [],
        ], 422);
    }
}
