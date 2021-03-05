<?php

namespace LarAPI\Modules\Auth\Support\DTOs;

use LarAPI\Modules\Common\Support\DTOs\DTOInterface;

class CreateUserDTO implements DTOInterface
{
    public const NAME                  = 'name';
    public const EMAIL                 = 'email';
    public const PASSWORD              = 'password';
    public const PASSWORD_CONFIRMATION = 'password_confirmation';
    public const ACTIVE                = 'active';
    public const ROLE                  = 'role_id';

    private string $name;
    private string $email;
    private string $password;
    private bool $active;
    private int $roleId;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CreateUserDTO
     */
    public function setName(string $name): CreateUserDTO
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return CreateUserDTO
     */
    public function setEmail(string $email): CreateUserDTO
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return CreateUserDTO
     */
    public function setPassword(string $password): CreateUserDTO
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return CreateUserDTO
     */
    public function setActive(bool $active): CreateUserDTO
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return int
     */
    public function getRoleId(): int
    {
        return $this->roleId;
    }

    /**
     * @param int $roleId
     * @return CreateUserDTO
     */
    public function setRoleId(int $roleId): CreateUserDTO
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
            self::EMAIL    => $this->email,
            self::PASSWORD => $this->password,
            self::ACTIVE   => $this->active,
            self::ROLE     => $this->roleId,
        ];
    }
}
