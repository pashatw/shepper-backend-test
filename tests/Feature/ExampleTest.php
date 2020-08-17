<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAuthenticationWorks()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer one'
        ])->get('/user');

        $response->assertStatus(200)
            ->assertJson([
                'id' => 1
            ]);
    }
}
