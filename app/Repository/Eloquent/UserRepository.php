<?php
declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Exception\NotFoundException;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function findAll(): array
    {
        return $this->model->get()->toArray();
    }

    public function create($data): object
    {
        return $this->model->create($data);
    }

    public function update(string $email,array $data): object
    {
        $user =  $this->model->where('email',$email)->first();
        $user->update($data);

        $user->refresh();

        return $user;
    }

    public function delete(string $email): bool
    {
        $user =  $this->model->where('email',$email)->first();
        if(!$user){
            throw new NotFoundException("User Not Found", 1);
        }

        return $user->delete();
    }
}
