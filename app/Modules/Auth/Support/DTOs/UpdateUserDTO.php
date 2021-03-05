<?php

namespace LarAPI\Modules\Auth\Support\DTOs;

use LarAPI\Modules\Common\Support\DTOs\DTOInterface;

class UpdateUserDTO implements DTOInterface
{
    public const NAME                  = 'name';
    public const PASSWORD              = 'password';
    public const PASSWORD_CONFIRMATION = 'password_confirmation';
    public const ACTIVE                = 'active';
    public const ROLE                  = 'role_id';

    private string $name;
    private ?string $password;
    private ?bool $active;
    private ?int $roleId;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return UpdateUserDTO
     */
    public function setName(string $name): UpdateUserDTO
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return UpdateUserDTO
     */
    public function setPassword(?string $password): UpdateUserDTO
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool|null $active
     * @return UpdateUserDTO
     */
    public function setActive(?bool $active): UpdateUserDTO
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRoleId(): ?int
    {
        return $this->roleId;
    }

    /**
     * @param int|null $roleId
     * @return UpdateUserDTO
     */
    public function setRoleId(?int $roleId): UpdateUserDTO
    {
        $this->roleId = $roleId;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::NAME     => $this->name,
            self::PASSWORD => $this->password,
            self::ACTIVE   => $this->active,
            self::ROLE     => $this->roleId,
        ];
    }
}
