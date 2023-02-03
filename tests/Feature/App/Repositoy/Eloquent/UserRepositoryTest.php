<?php

namespace Tests\Feature\App\Repositoy\Eloquent;

use App\Repository\Eloquent\UserRepository;
use App\Models\User;
use App\Repository\Contracts\UserRepositoryInterface;
use Illuminate\Cache\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        $this->repository = new UserRepository(new User());

        parent::setUp();
    }

    public function test_implements_interface()
    {
        $this->assertInstanceOf(
            UserRepositoryInterface::class,
            $this->repository
        );
    }

    public function test_find_all_empty()
    {
        $response = $this->repository->findAll();

        $this->assertIsArray($response);
        $this->assertCount(0,$response);
    }

    public function test_find_all()
    {
        User::factory()->count(10)->create();
        $response = $this->repository->findAll();

        $this->assertCount(10,$response);
    }

    public function test_create()
    {
        $data = [
            'name' => 'Matheus Oliveira',
            'email' => 'matheus.oliveira@gmail.com',
            'password' => bcrypt('123456')
        ];

        $response = $this->repository->create($data);

        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertDatabaseHas('users', [
            'email' => 'matheus.oliveira@gmail.com'
        ]);
    }
}