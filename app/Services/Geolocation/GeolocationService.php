<?php

namespace App\Services\Geolocation;

interface GeolocationService
{
    public function areCoordinatesInCountry(string $latitude, string $longitude, string $countryCode): bool;

    public function getLabelForCoordinates(string $latitude, string $longitude): string;
}