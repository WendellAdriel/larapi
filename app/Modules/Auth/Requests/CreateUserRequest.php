<?php


namespace LarAPI\Modules\Auth\Requests;

use Illuminate\Support\Facades\Hash;
use LarAPI\Core\Http\BaseRequest;
use LarAPI\Models\Auth\Role;
use LarAPI\Modules\Auth\Support\DTOs\CreateUserDTO;
use LarAPI\Modules\Common\Support\DTOs\DTOInterface;

class CreateUserRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            CreateUserDTO::NAME                  => ['required', 'string'],
            CreateUserDTO::EMAIL                 => ['required', 'email', 'unique:users,email'],
            CreateUserDTO::PASSWORD_CONFIRMATION => ['required', 'string'],
            CreateUserDTO::ACTIVE                => ['sometimes', 'boolean'],
            CreateUserDTO::ROLE                  => ['sometimes', 'integer'],
            CreateUserDTO::PASSWORD              => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/[a-z]/', 'regex:/[A-Z]/',
                'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getDTO(): DTOInterface
    {
        $dto = new CreateUserDTO();

        return $dto->setName($this->input($dto::NAME))
            ->setEmail($this->input($dto::EMAIL))
            ->setPassword(Hash::make($this->input($dto::PASSWORD)))
            ->setActive($this->input($dto::ACTIVE, true))
            ->setRoleId($this->input($dto::ROLE, Role::ROLE_NORMAL));
    }
}
