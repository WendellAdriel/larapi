<?php

namespace LarAPI\Modules\Auth\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use LarAPI\Modules\Auth\Services\AuthService;
use LarAPI\Core\Http\BaseController;
use Throwable;

class AuthController extends BaseController
{
    private AuthService $service;

    /**
     * AuthController constructor.
     * @param AuthService $service
     */
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * Gets a JWT via given credentials
     *
     * @OA\Post(
     *      tags={"Auth"},
     *      path="/v1/auth/login",
     *      description="Logs in a user and gets the JWT token",
     *
     *      @OA\RequestBody(
     *          description="User credentials",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="email", type="string"),
     *                  @OA\Property(property="password", type="string"),
     *              ),
     *          ),
     *      ),
     *
     *      @OA\Response(
     *          response="200",
     *          description="The JWT Token",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="access_token",
     *                      type="string",
     *                      description="The JWT Token",
     *                  ),
     *                  @OA\Property(
     *                      property="token_type",
     *                      type="string",
     *                      description="The Token type",
     *                  ),
     *                  @OA\Property(
     *                      property="expires_in",
     *                      type="integer",
     *                      description="The value in seconds for the token to expire",
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(response="401", description="Unauthorized"),
     * )
     *
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        $credentials = request(['email', 'password']);
        if (!$token = $this->service->getAPIToken($credentials)) {
            return $this->apiErrorResponse(AuthService::UNAUTHORIZED_MSG, null, Response::HTTP_UNAUTHORIZED);
        }

        return $this->apiSuccessResponse($this->service->buildTokenInfo($token));
    }

    /**
     * Invalidates the token
     *
     * @OA\Post(
     *      tags={"Auth"},
     *      path="/v1/auth/logout",
     *      description="Logs out a user and invalidates the JWT token",
     *      security={ "jwt": {} },
     *
     *      @OA\Response(
     *          response="200",
     *          description="Logout message",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="message", type="string"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(response="401", description="Unauthorized"),
     * )
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::logout();
        return $this->apiSuccessResponse(['message' => AuthService::LOGOUT_MSG]);
    }

    /**
     * Refreshes a token
     *
     * @OA\Post(
     *      tags={"Auth"},
     *      path="/v1/auth/refresh",
     *      description="Refresh the JWT Token, invalidating the old one and generating a new one",
     *      security={ "jwt": {} },
     *
     *      @OA\Response(
     *          response="200",
     *          description="The new JWT Token",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="access_token",
     *                      type="string",
     *                      description="The JWT Token",
     *                  ),
     *                  @OA\Property(
     *                      property="token_type",
     *                      type="string",
     *                      description="The Token type",
     *                  ),
     *                  @OA\Property(
     *                      property="expires_in",
     *                      type="integer",
     *                      description="The value in seconds for the token to expire",
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(response="401", description="Unauthorized"),
     * )
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        try {
            return $this->apiSuccessResponse($this->service->buildTokenInfo(Auth::refresh()));
        } catch (Throwable $exception) {
            return $this->apiErrorResponse(AuthService::UNAUTHORIZED_MSG, null, Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Gets the authenticated user
     *
     * @OA\Get(
     *      tags={"Logged User Info"},
     *      path="/v1/auth/me",
     *      description="Gets the logged User",
     *      security={ "jwt": {} },
     *
     *      @OA\Response(response="200", description="The logged user info",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(type="object",
     *                  @OA\Property(property="user", type="object",
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="uuid", type="string"),
     *                      @OA\Property(property="name", type="string"),
     *                      @OA\Property(property="email", type="string"),
     *                      @OA\Property(property="active", type="boolean"),
     *                      @OA\Property(property="role_id", type="integer"),
     *                      @OA\Property(property="settings", type="object"),
     *                      @OA\Property(property="last_login", type="string"),
     *                      @OA\Property(property="created_at", type="string"),
     *                      @OA\Property(property="updated_at", type="string"),
     *                      @OA\Property(property="is_admin", type="boolean"),
     *                      @OA\Property(property="is_manager", type="boolean"),
     *                      @OA\Property(property="is_user", type="boolean"),
     *                      @OA\Property(property="is_viewer", type="boolean"),
     *                      @OA\Property(property="role_label", type="string"),
     *                      @OA\Property(property="role", type="object",
     *                          @OA\Property(property="id", type="integer"),
     *                          @OA\Property(property="name", type="string"),
     *                          @OA\Property(property="created_at", type="string"),
     *                          @OA\Property(property="updated_at", type="string"),
     *                      ),
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(response="401", description="Unauthorized"),
     * )
     *
     * @return JsonResponse
     */
    public function loggedUser(): JsonResponse
    {
        return $this->apiSuccessResponse(['user' => Auth::user()]);
    }

    /**
     * Gets the authenticated user allowed roles
     *
     * @OA\Get(
     *      tags={"Logged User Info"},
     *      path="/v1/auth/me/roles",
     *      description="Gets the logged User allowed roles",
     *      security={ "jwt": {} },
     *
     *      @OA\Response(response="200", description="The logged user allowed roles",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(type="array",
     *                  @OA\Items(type="object",
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="name", type="string"),
     *                      @OA\Property(property="created_at", type="string"),
     *                      @OA\Property(property="updated_at", type="string"),
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(response="401", description="Unauthorized"),
     * )
     *
     * @return JsonResponse
     */
    public function loggedUserRoles(): JsonResponse
    {
        return $this->apiSuccessResponse($this->service->getUserAllowedRoles(Auth::user()));
    }
}
