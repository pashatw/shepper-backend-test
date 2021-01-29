<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\LocationResource;
use App\Services\Geolocation\GeolocationService;
use App\Http\Requests\LocationCreateRequest;
use App\Exceptions\MaxLocationException;
use App\Exceptions\ForeignCoordinatesException;
use App\Models\Location;
use App\Http\Requests\LocationUpdateRequest;
use App\Http\Requests\LocationDeleteRequest;

class LocationController extends Controller
{
    private $geolocation;

    public function __construct(GeolocationService $geolocation)
    {
        $this->geolocation = $geolocation;
    }

    public function detail(Request $request)
    {
        return $this->responseJson(LocationResource::collection($request->user()->locations));
    }

    public function create(LocationCreateRequest $request)
    {
        if (config('shepper.max_location_user') <= $request->user()->locations()->count()) {
            throw new MaxLocationException;
        }

        $location = $request->user()->locations()->create([
            'title' => $request->input('title'),
            'label' => $this->getLabel($request),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'radius'=> $request->input('radius'),
        ]);

        return $this->responseJson(LocationResource::make($location));
    }

    public function update(LocationUpdateRequest $request, Location $location)
    {
        $location->update([
            'title' => $request->input('title'),
            'label' => $this->getLabel($request),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'radius'=> $request->input('radius'),
        ]);

        return $this->responseJson(LocationResource::make($location));
    }

    public function delete(LocationDeleteRequest $request, Location $location)
    {
        $location->delete();
        return $this->responseJson([]);
    }

    public function getLabel(Request $request): ?string
    {
        if ($request->input('latitude') === null or $request->input('longitude') === null) {
            return null;
        }

        if ( ! $this->geolocation->areCoordinatesInCountry($request->input('latitude'), $request->input('longitude'), $request->user()->country_code)) {
            throw new ForeignCoordinatesException;
        }

        return $this->geolocation->getLabelForCoordinates($request->input('latitude'), $request->input('longitude'));
    }
}
