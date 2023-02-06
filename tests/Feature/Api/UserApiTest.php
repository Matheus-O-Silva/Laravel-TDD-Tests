<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    protected string $endpoint = '/api/users';

    public function test_paginate_empty()
    {
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertOk();
        $response->assertJsonCount(0,'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page'
            ],
            'data'
        ]);
        $response->assertJsonFragment([
            'total' => 0
        ]);

        $this->assertEquals(0, $response['meta']['total']);
    }

    public function test_paginate()
    {
        User::factory()->count(40)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15,'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page'
            ]
        ]);
        $response->assertJsonFragment([
            'total' => 40
        ]);

        $this->assertEquals(40, $response['meta']['total']);
        $this->assertEquals(1, $response['meta']['current_page']);
    }

    public function test_page_two()
    {
        User::factory()->count(20)->create();

        $response = $this->getJson("{$this->endpoint}?page=2");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5,'data');
    }

    /*
    public function test_create_validations()
    {
        //
    }
    */
}
