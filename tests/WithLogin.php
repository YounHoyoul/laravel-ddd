<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Src\Agenda\User\Application\Mappers\UserMapper;
use Src\Agenda\User\Domain\Factories\UserFactory;
use Src\Agenda\User\Infrastructure\EloquentModels\UserEloquentModel;

trait WithLogin
{
    use WithFaker, WithCompanies;

    /**
     * Create a new user instance.
     */
    protected function validCredentials(array $attributes = null): array
    {
        $password = $this->faker->password(8);
        $user = UserFactory::new($attributes);
        $userEloquent = UserMapper::toEloquent($user);

        $userEloquent->password = $password;
        $userEloquent->save();

        return [
            'id'         => $userEloquent->id,
            'company_id' => $userEloquent->company_id,
            'email'      => $user->email,
            'password'   => $password,
        ];
    }

    protected function newLoggedAdmin(): UserEloquentModel
    {
        $password = $this->faker->password(8);
        $user = UserFactory::new(['is_admin' => true]);
        $userEloquent = UserMapper::toEloquent($user);

        $userEloquent->password = $password;
        $userEloquent->save();

        return $userEloquent;
    }

    protected function newLoggedUser(): UserEloquentModel
    {
        // $company = $this->newCompany();
        // $credentials = $this->validCredentials(['is_admin' => false, 'company_id' => $company->id]);
        // $response = $this->post('auth/login', $credentials);
        // return ['token' => $this->getToken($response), ...$credentials];

        $company = $this->newCompany();
        $password = $this->faker->password(8);
        $user = UserFactory::new([
            'is_admin' => false, 
            'company_id' => $company->id
        ]);
        $userEloquent = UserMapper::toEloquent($user);

        $userEloquent->password = $password;
        $userEloquent->save();

        return $userEloquent;
    }

    protected function getToken(TestResponse $response)
    {
        $arResponse = json_decode($response->getContent(), true);
        return $arResponse['accessToken'];
    }
}