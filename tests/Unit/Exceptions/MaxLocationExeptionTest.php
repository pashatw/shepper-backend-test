<?php

namespace Tests\Unit\Exeptions;

use Tests\TestCase;
use App\Exceptions\MaxLocationException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MaxLocationExceptionTest extends TestCase
{
	protected $maxLocactionException;

    public function setUp(): void
    {
        parent::setUp();
        $this->maxLocactionException = new MaxLocationException();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    function testMaxLocation()
    {
        $request = new Request();
        $response = $this->maxLocactionException->toResponse($request);
        // print_r((array) $response->getData());
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame(
        [
            'success' => 0,
            'failed' => 1,
            'message' => "Cannot create location more than 5 locations",
            'data' => [],
        ], $response->getData(true));
    }
}
