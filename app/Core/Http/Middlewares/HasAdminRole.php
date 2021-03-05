<?php

namespace LarAPI\Core\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LarAPI\Core\Exceptions\AccessDeniedException;

class HasAdminRole
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws AccessDeniedException
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (empty($user)) {
            throw new AccessDeniedException();
        }
        if ($user->is_admin) {
            return $next($request);
        }
        throw new AccessDeniedException();
    }
}
