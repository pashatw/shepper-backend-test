<?php

namespace Tests\Unit\Controllers\API;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Location;
use App\Http\Resources\LocationResource;
use App\Http\Controllers\API\LocationController;
use Illuminate\Http\Request;
use App;

class LocationControllerTest extends TestCase
{
	private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['country_code' => 'GB']);
    }

    public function testDetailWoAuth()
    {
    	$response = $this->json('GET', route('api.location.detail'));
        $response->assertUnauthorized();
    }

    public function testCreateWithErrorMinTitle()
    {
        $this->actingAs($this->user, 'api');
        // min char - title
        $minTitle = [
        	'title' => Str::random(2)
        ];
        $response = $this->json('POST', route('api.location.create'), $minTitle);
        $response->assertStatus(422)
	        ->assertJson([
	                'success' => 0,
	                'failed' => 1,
	                'message' => 'The given data is invalid',
	                'data' => [
	                    'errors' => [
	                    	'title' => ['The title must be between 3 and 30 characters.']
	                    ],
	                ]
	            ]);
	}

	public function testCreateWithErrorMaxTitle()
    {
    	$this->actingAs($this->user, 'api');
	    // max char - title
        $maxTitle = [
        	'title' => Str::random(40)
        ];
        $response = $this->json('POST', route('api.location.create'), $maxTitle);
        $response->assertStatus(422)
	        ->assertJson([
	                'success' => 0,
	                'failed' => 1,
	                'message' => 'The given data is invalid',
	                'data' => [
	                    'errors' => [
	                    	'title' => ['The title must be between 3 and 30 characters.']
	                    ],
	                ]
	            ]);
	}

	public function testCreateWithErrorNumRadius()
    {
    	$this->actingAs($this->user, 'api');
	    // check numeric - radius
        $numericRadius = [
        	'radius' => Str::random(3)
        ];
        $response = $this->json('POST', route('api.location.create'), $numericRadius);
        $response->assertStatus(422)
	        ->assertJson([
	                'success' => 0,
	                'failed' => 1,
	                'message' => 'The given data is invalid',
	                'data' => [
	                    'errors' => [
	                    	'radius' => ['The radius must be a number.']
	                    ],
	                ]
	            ]);
    }

	public function testCreateWithErrorMinRadius()
    {
    	$this->actingAs($this->user, 'api');
	    // check min - radius
        $minRadius = [
        	'radius' => 0.45
        ];
        $response = $this->json('POST', route('api.location.create'), $minRadius);
        $response->assertStatus(422)
	        ->assertJson([
	                'success' => 0,
	                'failed' => 1,
	                'message' => 'The given data is invalid',
	                'data' => [
	                    'errors' => [
	                    	'radius' => ['The radius must be between 0.5 and 50.']
	                    ],
	                ]
	            ]);
	}

	public function testCreateWithErrorMaxRadius()
    {
    	$this->actingAs($this->user, 'api');
	    // check max - radius
        $maxRadius = [
        	'radius' => 50.1
        ];
        $response = $this->json('POST', route('api.location.create'), $maxRadius);
        $response->assertStatus(422)
	        ->assertJson([
	                'success' => 0,
	                'failed' => 1,
	                'message' => 'The given data is invalid',
	                'data' => [
	                    'errors' => [
	                    	'radius' => ['The radius must be between 0.5 and 50.']
	                    ],
	                ]
	            ]);
	}

	public function testCreateWithInvalidLatLng()
    {
    	$this->actingAs($this->user, 'api');
	    // check latlng
        $checkLatLng = [
        	'title' => 'Loc1',
            'latitude' => '1234',
            'longitude' => '-1234',
            'radius' => 25.0,
        ];
        $response = $this->json('POST', route('api.location.create'), $checkLatLng);
        $response->assertStatus(422)
	        ->assertJson([
	                'success' => 0,
	                'failed' => 1,
	                'message' => 'The coordinates [1234,-1234] are invalid.',
	                'data' => []
	            ]);
    }

    public function testCreateSucecss()
    {
    	$this->actingAs($this->user, 'api');
    	$loc1 = [
        	'title' => 'Loc1',
            'latitude' => '51.499479',
            'longitude' => '-0.085499',
            'radius' => 25.0,
        ];
        $response = $this->json('POST', route('api.location.create'), $loc1);
        $response->assertStatus(200);
    }

    public function testCreateWithLimit()
    {
    	$this->actingAs($this->user, 'api');
    	factory(Location::class, 5)->create(['user_id' => $this->user->id]);

    	$loc1 = [
        	'title' => 'Loc1',
            'latitude' => '51.499479',
            'longitude' => '-0.085499',
            'radius' => 25.0,
        ];
        $response = $this->json('POST', route('api.location.create'), $loc1);
        $response->assertStatus(422)
	        ->assertJson([
            'success' => 0,
            'failed' => 1,
            'message' => "Cannot create location more than 5 locations",
            'data' => [],
        ]);
    }

    public function testCreateWithDifferentCountry()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->json('POST', route('api.location.create'), [
            'title' => 'Home',
            'latitude' => '48.852774',
            'longitude' => '2.345620',
            'radius' => 25.0,
        ]);

	    $response->assertStatus(422)
	        ->assertJson([
            'success' => 0,
            'failed' => 1,
            'message' => "The coordinates does not belong to user's country: GB",
            'data' => [],
        ]);
    }

    public function testGetDetail()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->json('GET', route('api.location.detail'));
        $response->assertStatus(200);
    }

    public function testUpdateOtherUser()
    {
        $user2 = factory(User::class)->create();
        $locationU2 = factory(Location::class)->create(['user_id' => $user2->id]);
        
        $this->actingAs($this->user, 'api');

        $response = $this->json('PUT', route('api.location.update', [$locationU2]));

        $response->assertForbidden();
    }

    public function testUpdateWithErrorMinTitle()
    {
        $this->actingAs($this->user, 'api');
        $location = factory(Location::class)->create(['user_id' => $this->user->id]);
        
        // min char - title
        $minTitle = [
        	'title' => Str::random(2)
        ];
        $response = $this->json('PUT', route('api.location.update', $location), $minTitle);
        $response->assertStatus(422)
	        ->assertJson([
	                'success' => 0,
	                'failed' => 1,
	                'message' => 'The given data is invalid',
	                'data' => [
	                    'errors' => [
	                    	'title' => ['The title must be between 3 and 30 characters.']
	                    ],
	                ]
	            ]);
	}

	public function testUpdateWithErrorMaxTitle()
    {
    	$this->actingAs($this->user, 'api');
        $location = factory(Location::class)->create(['user_id' => $this->user->id]);
	    // max char - title
        $maxTitle = [
        	'title' => Str::random(40)
        ];
        $response = $this->json('PUT', route('api.location.update', $location), $maxTitle);
        $response->assertStatus(422)
	        ->assertJson([
	                'success' => 0,
	                'failed' => 1,
	                'message' => 'The given data is invalid',
	                'data' => [
	                    'errors' => [
	                    	'title' => ['The title must be between 3 and 30 characters.']
	                    ],
	                ]
	            ]);
	}

	public function testUpdateWithErrorMinRadius()
    {
    	$this->actingAs($this->user, 'api');
        $location = factory(Location::class)->create(['user_id' => $this->user->id]);
        $minRadius = [
        	'radius' => 0.45
        ];
        $response = $this->json('PUT', route('api.location.update', $location), $minRadius);
        $response->assertStatus(422)
	        ->assertJson([
	                'success' => 0,
	                'failed' => 1,
	                'message' => 'The given data is invalid',
	                'data' => [
	                    'errors' => [
	                    	'radius' => ['The radius must be between 0.5 and 50.']
	                    ],
	                ]
	            ]);
	}

	public function testUpdateWithErrorMaxnRadius()
    {
    	$this->actingAs($this->user, 'api');
        $location = factory(Location::class)->create(['user_id' => $this->user->id]);
        $data = [
        	'radius' => 50.1
        ];
        $response = $this->json('PUT', route('api.location.update', $location), $data);
        $response->assertStatus(422)
	        ->assertJson([
	                'success' => 0,
	                'failed' => 1,
	                'message' => 'The given data is invalid',
	                'data' => [
	                    'errors' => [
	                    	'radius' => ['The radius must be between 0.5 and 50.']
	                    ],
	                ]
	            ]);
	}

	public function testUpdateSuccess()
    {
    	$this->actingAs($this->user, 'api');
        $location = factory(Location::class)->create(['user_id' => $this->user->id]);
        $data = [
        	'title' => "Loc2",
        	'radius' => 50.0,
        	'latitude' => '52.486059',
            'longitude' => '-1.891002',
        ];
        $response = $this->json('PUT', route('api.location.update', $location), $data);
        $location_new = Location::find($location->id); 
        $response->assertStatus(200);
        $this->assertSame($data['title'], $location_new->title);
        $this->assertSame($data['radius'], $location_new->radius);
	}

	public function testDeleteOtherUser()
    {
        $user2 = factory(User::class)->create();
        $locationU2 = factory(Location::class)->create(['user_id' => $user2->id]);
        $this->actingAs($this->user, 'api');

        $response = $this->json('DELETE', route('api.location.delete', [$locationU2]));

        $response->assertForbidden();
    }

    public function testDeleteSuccess()
    {
        $location = factory(Location::class)->create(['user_id' => $this->user->id]);
        $this->actingAs($this->user, 'api');

        $response = $this->json('DELETE', route('api.location.delete', [$location]));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('location', ['id' => $location->id]);
    }

    public function testGetLabelNull()
    {
    	$geo = App::make('App\Services\Geolocation\GeolocationService');
    	$request = new Request();
        $locationController = new LocationController($geo);
        $response = $locationController->getLabel($request);
        $this->assertSame(null, $response);
    }
}
