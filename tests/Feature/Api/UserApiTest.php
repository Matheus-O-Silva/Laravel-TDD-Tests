<?php
declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    protected string $endpoint = '/api/users';

    /**
     * @dataProvider dataProviderPagination
     */
    public function test_paginate(
        int $total = 40,
        int $page = 1,
        int $totalPage = 15
    )
    {
        User::factory()->count($total)->create();

        $response = $this->getJson("{$this->endpoint}?page={$page}");
        $response->assertStatus(Response::HTTP_OK); // $response->assertOk();
        $response->assertJsonCount($totalPage,'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page'
            ],
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email'
                ]
            ]
        ]);
        $response->assertJsonFragment([
            'total' => $total
        ]);

        $this->assertIsInt($page);
        $this->assertEquals($total, $response['meta']['total']);
        $this->assertEquals($page, $response['meta']['current_page']);
    }

    public function dataProviderPagination(): array
    {
        return [
            'test total 40 users page one' => ['total' => 40, 'page' => 1, 'totalPage' => 15],
            'test total 40 users page one' => ['total' => 40, 'page' => 1, 'totalPage' => 15],
            'test total 40 users page one' => ['total' => 40, 'page' => 1, 'totalPage' => 15]
        ];
    }

    public function test_create_validations()
    {
        $payload = [
            'name' => 'Matt',
            'email' => 'math@test.com',
            'password' => '12345678',
        ];

        $response = $this->postJson($this->endpoint, $payload);
        $response->assertStatus(Response::HTTP_CREATED);
    }


    /*
    public function dataProviderCreateUser(): array
    {
        return [

        ];
    }
    */
}
