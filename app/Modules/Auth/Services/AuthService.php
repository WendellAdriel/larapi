<?php

namespace LarAPI\Modules\Auth\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use LarAPI\Repositories\Auth\UserRepository;

class AuthService
{
    public const UNAUTHORIZED_MSG = 'Unauthorized';
    public const LOGOUT_MSG       = 'Successfully logged out';

    private UserRepository $userRepository;

    /**
     * AuthService constructor
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Gets the JWT API Token for the given credentials
     *
     * @param array $credentials
     * @return string|null
     */
    public function getAPIToken(array $credentials): ?string
    {
        if (!Auth::validate($credentials)) {
            return null;
        }

        $user = $this->userRepository->getByOrFail('email', $credentials['email']);

        $user->last_login = new Carbon();
        $user->save();

        return Auth::attempt($credentials);
    }
}
