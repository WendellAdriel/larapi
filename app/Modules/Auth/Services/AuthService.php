<?php

namespace LarAPI\Modules\Auth\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use LarAPI\Models\Auth\Role;
use LarAPI\Models\Auth\User;
use LarAPI\Repositories\Auth\RoleRepository;
use LarAPI\Repositories\Auth\UserRepository;

class AuthService
{
    public const UNAUTHORIZED_MSG = 'Invalid Credentials';
    public const LOGOUT_MSG       = 'Successfully logged out';

    private UserRepository $userRepository;
    private RoleRepository $roleRepository;

    /**
     * AuthService constructor
     *
     * @param UserRepository $userRepository
     * @param RoleRepository $roleRepository
     */
    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
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

    /**
     * Gets the token array structure
     *
     * @param string $token
     * @return array
     */
    public function buildTokenInfo(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::factory()->getTTL() * 60
        ];
    }

    /**
     * @param User $user
     * @return Collection
     */
    public function getUserAllowedRoles(User $user): Collection
    {
        if ($user->is_viewer) {
            return collect();
        }

        return Cache::remember("{$user->id}_roles", config('cache.store_time'), function () use ($user) {
            $roles = $this->roleRepository->all();
            if ($user->is_admin) {
                return $roles;
            }

            if ($user->is_manager) {
                return $roles->whereNotIn('id', [Role::ROLE_ADMIN]);
            }

            return $roles->whereNotIn('id', [Role::ROLE_MANAGER, Role::ROLE_ADMIN]);
        });
    }
}
