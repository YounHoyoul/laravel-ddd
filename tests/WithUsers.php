<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Src\Agenda\User\Application\Mappers\UserMapper;
use Src\Agenda\User\Domain\Factories\UserFactory;
use Src\Agenda\User\Domain\Model\User;
use Src\Agenda\User\Domain\Repositories\AvatarRepositoryInterface;
use Src\Agenda\User\Infrastructure\EloquentModels\UserEloquentModel;

trait WithUsers
{
    use WithFaker;

    protected function newUser(): User
    {
        return UserMapper::fromEloquent(
            $this->newUserEloquent(),
            app()->make(AvatarRepositoryInterface::class)
        );
    }

    protected function newUserEloquent(array $attributes = null) : UserEloquentModel
    {
        $user = UserFactory::new($attributes);
        $userEloquent = UserMapper::toEloquent($user);
        $userEloquent->password = Hash::make('password'); // password
        $userEloquent->email_verified_at = $attributes['email_verified_at'] ?? null;
        $userEloquent->save();

        return $userEloquent;
    }

    protected function createRandomUsers($usersNumber = 1): array
    {
        $user_ids = [];
        foreach (range(1, $usersNumber) as $_) {
            $user_ids[] = $this->newUserEloquent()->id;
        }
        return $user_ids;
    }
}