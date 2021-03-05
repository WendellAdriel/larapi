<?php

namespace LarAPI\Modules\Auth\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use LarAPI\Models\Auth\Role;
use LarAPI\Models\Auth\User;
use LarAPI\Modules\Auth\Support\DTOs\CreateUserDTO;
use LarAPI\Modules\Auth\Support\DTOs\UpdateUserDTO;
use LarAPI\Modules\Common\Support\DTOs\CommonTableDTO;
use LarAPI\Modules\Common\Support\Paginator;
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
     * @param CommonTableDTO $dto
     * @return array
     */
    public function getAllUsers(CommonTableDTO $dto): array
    {
        $users     = $this->repository->all();
        $formatted = $dto->applyFilter($users, ['name', 'email', 'role_label']);
        $formatted = $dto->applySort($formatted);
        return Paginator::manualPaginate($formatted, $users->count(), $dto);
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

    /**
     * @param string $userUuid
     * @return User
     * @throws ModelNotFoundException
     */
    public function getUser(string $userUuid): User
    {
        return $this->repository->getByOrFail('uuid', $userUuid);
    }

    /**
     * @param User          $user
     * @param string        $userUuid
     * @param UpdateUserDTO $dto
     * @return int
     */
    public function updateUser(User $user, string $userUuid, UpdateUserDTO $dto): int
    {
        if (!$user->is_admin && !is_null($dto->getRoleId())) {
            if ($dto->getRoleId() === Role::ROLE_ADMIN) {
                $dto->setRoleId(Role::ROLE_NORMAL);
            }
            if ($dto->getRoleId() === Role::ROLE_MANAGER && !$user->is_manager) {
                $dto->setRoleId(Role::ROLE_NORMAL);
            }
        }

        $params = collect($dto->toArray())->filter()->toArray();
        return $this->repository->updateBy('uuid', $userUuid, $params);
    }

    /**
     * @param string $userUuid
     * @return mixed
     */
    public function deleteUser(string $userUuid)
    {
        return $this->repository->deleteBy('uuid', $userUuid);
    }
}
