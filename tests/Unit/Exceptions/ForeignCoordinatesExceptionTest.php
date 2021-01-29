<?php

namespace Tests\Unit\Exceptions;

use Tests\TestCase;
use App\Exceptions\ForeignCoordinatesException;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForeignCoordinatesExceptionTest extends TestCase
{
    protected $foreignException;

    public function setUp(): void
    {
        parent::setUp();
        $this->foreignException = new ForeignCoordinatesException();
    }

    public function testForeignCoordinate()
    {
    	$user = factory(User::class)->make(['country_code' => 'GB']);
    	$request = Request::create('/', 'GET');
    	$request->setUserResolver(function () use ($user) {
            return $user;
        });
    	$response = $this->foreignException->toResponse($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame(
        [
            'success' => 0,
            'failed' => 1,
            'message' => "The coordinates does not belong to user's country: GB",
            'data' => [],
        ], $response->getData(true));
    }
}
