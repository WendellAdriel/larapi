<?php

namespace LarAPI\Auth\Controllers;

use Throwable;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

use LarAPI\Core\Http\BaseController;
use LarAPI\Auth\Services\AuthService;

class AuthController extends BaseController
{
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
     * @param AuthService $authService
     * @return JsonResponse
     */
    public function login(AuthService $authService): JsonResponse
    {
        $credentials = request(['email', 'password']);
        if (!$token = $authService->getAPIToken($credentials)) {
            return $this->apiErrorResponse(AuthService::UNAUTHORIZED_MSG, null, SymfonyResponse::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
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
            return $this->respondWithToken(Auth::refresh());
        } catch (Throwable $exception) {
            return $this->apiErrorResponse(AuthService::UNAUTHORIZED_MSG, null, SymfonyResponse::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Gets the token array structure
     *
     * @param string $token
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return $this->apiSuccessResponse([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::factory()->getTTL() * 60
        ]);
    }
}
