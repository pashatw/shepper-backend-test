<?php

namespace App\Services\Geolocation;

use Illuminate\Contracts\Support\Responsable;

class InvalidCoordinatesException extends \RuntimeException implements Responsable
{
    /**
     * {@inheritDoc}
     */
    public function toResponse($request)
    {
        return response()->json([
            'success' => 0,
            'failed' => 1,
            'message' => sprintf('The coordinates [%s,%s] are invalid.', $request->input('latitude'), $request->input('longitude')),
            'data' => [],
        ], 422);
    }
}