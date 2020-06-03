<?php

namespace LarAPI\Auth\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use LarAPI\Core\Http\BaseController;

class UserController extends BaseController
{
    /**
     * Gets the authenticated user
     *
     * @OA\Get(
     *      tags={"Logged User Info"},
     *      path="/v1/auth/me",
     *      description="Gets the logged User",
     *      security={ "jwt": {} },
     *
     *      @OA\Response(response="200", description="The logged in info",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(type="object",
     *                  @OA\Property(property="user", type="object", description="Logged in user",
     *                      @OA\Property(property="id", type="string"),
     *                      @OA\Property(property="name", type="string"),
     *                      @OA\Property(property="email", type="string"),
     *                      @OA\Property(property="email_verified_at", type="string"),
     *                      @OA\Property(property="active", type="integer"),
     *                      @OA\Property(property="role_id", type="integer"),
     *                      @OA\Property(property="last_login", type="string"),
     *                      @OA\Property(property="settings", type="object"),
     *                      @OA\Property(property="created_at", type="string"),
     *                      @OA\Property(property="updated_at", type="string"),
     *                      @OA\Property(property="is_admin", type="boolean"),
     *                      @OA\Property(property="is_manager", type="boolean"),
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(response="401", description="Unauthorized"),
     * )
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->apiSuccessResponse(Auth::user());
    }
}
