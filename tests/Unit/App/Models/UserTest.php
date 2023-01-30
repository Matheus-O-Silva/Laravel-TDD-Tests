<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected function model(): User
    {
        return new User();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_traits()
    {
        $traits = array_keys(class_uses($this->model()));

        $expectedTraits = [
            HasApiTokens::class,
            HasFactory::class,
            Notifiable::class
        ];

        $this->assertEquals($expectedTraits, $traits);
    }

    public function test_fillable()
    {
        $fillable = $this->model()->getFillable();

        $expectedFillable = [
            'name',
            'email',
            'password'
        ];

        $this->assertEquals($expectedFillable, $fillable);
    }
}
