<?php

namespace Src\Agenda\User\Application\UseCases\Queries;

use Src\Agenda\User\Domain\Policies\UserPolicy;
use Src\Agenda\User\Domain\Repositories\UserRepositoryInterface;
use Src\Common\Domain\QueryInterface;

class FindAllUsersQuery implements QueryInterface
{
    private UserRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = app()->make(UserRepositoryInterface::class);
    }

    public function handle(): array
    {
        authorize('findAll', UserPolicy::class);

        return $this->repository->findAll();
    }
}
