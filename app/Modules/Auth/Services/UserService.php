<?php

namespace LarAPI\Modules\Auth\Services;

use LarAPI\Models\Auth\Role;
use LarAPI\Models\Auth\User;
use LarAPI\Modules\Auth\Support\DTOs\CreateUserDTO;
use LarAPI\Repositories\Auth\UserRepository;

class UserService
{
    private UserRepository $repository;

    /**
     * UserService constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param User $user
     * @param CreateUserDTO $dto
     * @return User
     */
    public function createUser(User $user, CreateUserDTO $dto): User
    {
        if (!$user->is_admin) {
            if ($dto->getRoleId() === Role::ROLE_ADMIN) {
                $dto->setRoleId(Role::ROLE_NORMAL);
            }
            if ($dto->getRoleId() === Role::ROLE_MANAGER && !$user->is_manager) {
                $dto->setRoleId(Role::ROLE_NORMAL);
            }
        }

        return $this->repository->create($dto->toArray());
    }
}
