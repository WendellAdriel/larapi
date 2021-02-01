<?php


namespace LarAPI\Modules\Auth\Requests;

use Illuminate\Support\Facades\Hash;
use LarAPI\Core\Http\BaseRequest;
use LarAPI\Modules\Auth\Support\DTOs\UpdateUserDTO;
use LarAPI\Modules\Common\Support\DTOs\DTOInterface;

class UpdateUserRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            UpdateUserDTO::NAME                  => ['required', 'string'],
            UpdateUserDTO::PASSWORD_CONFIRMATION => ['required_with:' . UpdateUserDTO::PASSWORD, 'string'],
            UpdateUserDTO::ACTIVE                => ['sometimes', 'boolean'],
            UpdateUserDTO::ROLE                  => ['sometimes', 'integer'],
            UpdateUserDTO::PASSWORD              => [
                'sometimes', 'nullable', 'string', 'min:8', 'confirmed',
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
        $dto      = new UpdateUserDTO();
        $password = $this->input($dto::PASSWORD);

        return $dto->setName($this->input($dto::NAME))
            ->setPassword(!is_null($password) ? Hash::make($password) : null)
            ->setActive($this->input($dto::ACTIVE))
            ->setRoleId($this->input($dto::ROLE));
    }
}
