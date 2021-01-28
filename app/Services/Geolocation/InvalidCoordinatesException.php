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
            'errors' => [
                'general' => [sprintf('The coordinates [%s,%s] are invalid.', $request->input('latitude'), $request->input('longitude'))],
            ]
        ], 422);
    }
}