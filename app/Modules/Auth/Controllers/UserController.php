<?php

namespace LarAPI\Modules\Auth\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use LarAPI\Core\Http\BaseController;
use LarAPI\Modules\Auth\Requests\CreateUserRequest;
use LarAPI\Modules\Auth\Services\UserService;

class UserController extends BaseController
{
    private UserService $service;

    /**
     * UserController constructor.
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
    }

    /**
     * Creates a new user
     *
     * @OA\Post(
     *      tags={"Users Management"},
     *      path="/v1/users",
     *      description="Creates a new user",
     *      security={ "jwt": {} },
     *
     *      @OA\RequestBody(description="Info needed to create a new user",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(type="object", required={"name", "email", "password", "password_confirmation"},
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="email", type="string"),
     *                  @OA\Property(property="password", type="string", description="Must contain at least one lowercase letter, one uppercase letter, one number and one special character"),
     *                  @OA\Property(property="password_confirmation", type="string"),
     *                  @OA\Property(property="active", type="boolean", default=true),
     *                  @OA\Property(property="role_id", type="integer", default=3),
     *              ),
     *          ),
     *      ),
     *
     *     @OA\Response(response="201", description="The success response",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(type="object",
     *                  @OA\Property(property="success", type="boolean"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(response="400", description="Invalid Request"),
     *      @OA\Response(response="401", description="Unauthorized"),
     *      @OA\Response(response="403", description="Access Denied"),
     *      @OA\Response(response="422", description="Data is invalid"),
     * )
     *
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function create(CreateUserRequest $request): JsonResponse
    {
        $this->service->createUser(Auth::user(), $request->getDTO());
        return $this->apiSimpleSuccessResponse();
    }

    public function show(string $uuid): JsonResponse
    {
    }

    public function update(string $uuid): JsonResponse
    {
    }

    public function delete(string $uuid): JsonResponse
    {
    }
}
