<?php
declare(strict_types=1);

namespace Tests\Feature\App\Repositoy\Eloquent;

use App\Repository\Eloquent\UserRepository;
use App\Models\User;
use App\Repository\Contracts\UserRepositoryInterface;
use Illuminate\Cache\Repository;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\FlareClient\Http\Exceptions\NotFound;
use App\Repository\Exception\NotFoundException;
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



    public function test_create_exception()
    {
        $this->expectException(QueryException::class);

        $data = [
            'name' => 'Matheus Oliveira',
            //'email' => 'matheus.oliveira@gmail.com',
            'password' => bcrypt('123456')
        ];

        $response = $this->repository->create($data);

        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertDatabaseHas('users', [
            'email' => 'matheus.oliveira@gmail.com'
        ]);
    }

    public function test_update()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'new name',
        ];

        $response = $this->repository->update($user->email, $data);

        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertDatabaseHas('users', [
            'name' => 'new name'
        ]);
    }

    public function test_delete()
    {
        $user = User::factory()->create();

        $deleted = $this->repository->delete($user->email);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('users', [
            'email' => $user->email
        ]);
    }

    public function test_delete_not_found()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->delete('fake_email');
    }

    public function test_find()
    {
        $user = User::factory()->create();

        $response = $this->repository->find($user->email);

        $this->assertIsObject($response);
        $this->assertIsObject($response);
    }

    public function test_find_not_found()
    {
        $response = $this->repository->find('fake_mail');

        $this->assertNull($response);
    }
}
