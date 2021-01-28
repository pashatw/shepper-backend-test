<?php

namespace App\Services\Geolocation;

class LocalGeolocationService implements GeolocationService
{
    /**
     * @var array
     */
    protected $places = [
        [
            'latitude' => '51.499479',
            'longitude' => '-0.085499',

            'label' => 'London',
            'country_code' => 'GB',
        ],
        [
            'latitude' => '52.486059',
            'longitude' => '-1.891002',

            'label' => 'Birmingham',
            'country_code' => 'GB',
        ],
        [
            'latitude' => '53.799102',
            'longitude' => '-1.548120',

            'label' => 'Leeds',
            'country_code' => 'GB',
        ],
        [
            'latitude' => '48.852774',
            'longitude' => '2.345620',

            'label' => 'Paris',
            'country_code' => 'FR',
        ],
        [
            'latitude' => '50.109852',
            'longitude' => '8.681891',

            'label' => 'Frankfurt',
            'country_code' => 'DE',
        ],
    ];

    public function areCoordinatesInCountry(string $latitude, string $longitude, string $countryCode): bool
    {
        $place = $this->getPlace($latitude, $longitude);

        return $place['country_code'] === $countryCode;
    }

    public function getLabelForCoordinates(string $latitude, string $longitude): string
    {
        $place = $this->getPlace($latitude, $longitude);

        return sprintf('%s, %s', $place['label'], $place['country_code']);
    }

    private function getPlace(string $latitude, string $longitude): array
    {
        $place =  collect($this->places)->first(
            function (array $place) use ($latitude, $longitude) {
                return $place['latitude'] === $latitude and $place['longitude'] === $longitude;
            }
        );

        if (is_null($place)) {
            throw new InvalidCoordinatesException;
        }

        return $place;
    }
}