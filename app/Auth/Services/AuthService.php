<?php

namespace LarAPI\Auth\Services;

class AuthService
{
    const UNAUTHORIZED_MSG = 'Unauthorized';
    const LOGOUT_MSG       = 'Successfully logged out';

    /**
     * Gets the JWT API Token for the given credentials
     *
     * @param array $credentials
     * @return string|null
     */
    public function getAPIToken(array $credentials): ?string
    {
        if (!auth()->validate($credentials)) {
            return null;
        }

        // DO ANY CUSTOM LOGIC HERE

        return auth()->attempt($credentials);
    }
}
