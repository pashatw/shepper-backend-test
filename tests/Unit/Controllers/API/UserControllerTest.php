<?php

namespace Tests\Unit\Controllers\API;

use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testDetailWoAuth()
    {
    	$response = $this->json('GET', route('api.user.detail'));
        $response->assertUnauthorized();
    }

    public function testDetailAuth()
    {
    	$response = $this->withHeaders([
            'Authorization' => 'Bearer one'
        ])->get('/user');

        $response->assertStatus(200)
            ->assertJson([
                'success' => 1,
                'failed' => 0,
                'message' => null,
                'data' => [
                    'id' => 1,
                ]
            ]);
    }
}
