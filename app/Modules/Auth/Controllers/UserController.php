<?php

namespace LarAPI\Modules\Auth\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use LarAPI\Core\Http\BaseController;
use LarAPI\Modules\Auth\Requests\CreateUserRequest;
use LarAPI\Modules\Auth\Requests\UpdateUserRequest;
use LarAPI\Modules\Auth\Services\UserService;
use LarAPI\Modules\Common\Requests\CommonTableRequest;

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

    /**
     * Gets the list of users
     *
     * @OA\Get(
     *      tags={"Users Management"},
     *      path="/v1/users",
     *      description="Gets the list of users",
     *      security={ "jwt": {} },
     *
     *      @OA\Parameter(name="page", in="query", required=false,
     *          @OA\Schema(type="integer", default=1),
     *      ),
     *      @OA\Parameter(name="per_page", in="query", required=false,
     *          @OA\Schema(type="integer", default=20),
     *      ),
     *      @OA\Parameter(name="sort", in="query", required=false,
     *          @OA\Schema(type="object",
     *              @OA\Property(property="field", type="string", description="Field name to use in sort"),
     *              @OA\Property(property="order", type="string", description="Order: 'asc' or 'desc'"),
     *          ),
     *      ),
     *      @OA\Parameter(name="search", in="query", required=false, description="Term to search",
     *          @OA\Schema(type="string"),
     *      ),
     *
     *     @OA\Response(response="200", description="The list of users",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(type="object",
     *                  @OA\Property(property="data", type="array",
     *                      @OA\Items(type="object",
     *                          @OA\Property(property="id", type="integer"),
     *                          @OA\Property(property="uuid", type="string"),
     *                          @OA\Property(property="name", type="string"),
     *                          @OA\Property(property="email", type="string"),
     *                          @OA\Property(property="active", type="boolean"),
     *                          @OA\Property(property="role_id", type="integer"),
     *                          @OA\Property(property="settings", type="object"),
     *                          @OA\Property(property="last_login", type="string"),
     *                          @OA\Property(property="created_at", type="string"),
     *                          @OA\Property(property="updated_at", type="string"),
     *                          @OA\Property(property="is_admin", type="boolean"),
     *                          @OA\Property(property="is_manager", type="boolean"),
     *                          @OA\Property(property="is_user", type="boolean"),
     *                          @OA\Property(property="is_viewer", type="boolean"),
     *                          @OA\Property(property="role_label", type="string"),
     *                          @OA\Property(property="role", type="object",
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="name", type="string"),
     *                              @OA\Property(property="created_at", type="string"),
     *                              @OA\Property(property="updated_at", type="string"),
     *                          ),
     *                      ),
     *                  ),
     *                  @OA\Property(property="pagination", type="object",
     *                      @OA\Property(property="page_count", type="integer"),
     *                      @OA\Property(property="total", type="integer", description="The filtered total count"),
     *                      @OA\Property(property="total_all", type="integer", description="The unfiltered total count"),
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(response="400", description="Invalid Request"),
     *      @OA\Response(response="401", description="Unauthorized"),
     *      @OA\Response(response="403", description="Access Denied"),
     *      @OA\Response(response="404", description="Resource not found")
     * )
     *
     * @param CommonTableRequest $request
     * @return JsonResponse
     */
    public function index(CommonTableRequest $request): JsonResponse
    {
        return $this->apiSuccessResponse($this->service->getAllUsers($request->getDTO()));
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

    /**
     * Gets the info of a specific user
     *
     * @OA\Get(
     *      tags={"Users Management"},
     *      path="/v1/users/{uuid}",
     *      description="Gets the info of a specific user",
     *      security={ "jwt": {} },
     *
     *      @OA\Parameter(name="uuid", in="path", required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *
     *      @OA\Response(response="200", description="The user info",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(type="object",
     *                  @OA\Property(property="id", type="integer"),
     *                  @OA\Property(property="uuid", type="string"),
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="email", type="string"),
     *                  @OA\Property(property="active", type="boolean"),
     *                  @OA\Property(property="role_id", type="integer"),
     *                  @OA\Property(property="settings", type="object"),
     *                  @OA\Property(property="last_login", type="string"),
     *                  @OA\Property(property="created_at", type="string"),
     *                  @OA\Property(property="updated_at", type="string"),
     *                  @OA\Property(property="is_admin", type="boolean"),
     *                  @OA\Property(property="is_manager", type="boolean"),
     *                  @OA\Property(property="is_user", type="boolean"),
     *                  @OA\Property(property="is_viewer", type="boolean"),
     *                  @OA\Property(property="role_label", type="string"),
     *                  @OA\Property(property="role", type="object",
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="name", type="string"),
     *                      @OA\Property(property="created_at", type="string"),
     *                      @OA\Property(property="updated_at", type="string"),
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(response="400", description="Invalid Request"),
     *      @OA\Response(response="401", description="Unauthorized"),
     *      @OA\Response(response="403", description="Access Denied"),
     *      @OA\Response(response="404", description="Resource not found"),
     * )
     *
     * @param string $uuid
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function show(string $uuid): JsonResponse
    {
        return $this->apiSuccessResponse($this->service->getUser($uuid));
    }

    /**
     * Updates the info of a specific user
     *
     * @OA\Put(
     *      tags={"Users Management"},
     *      path="/v1/users/{uuid}",
     *      description="Updates the info of a specific user",
     *      security={ "jwt": {} },
     *
     *      @OA\RequestBody(description="Info needed to update the user info",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(type="object", required={"name"},
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="password", type="string", description="Must contain at least one lowercase letter, one uppercase letter, one number and one special character"),
     *                  @OA\Property(property="password_confirmation", type="string"),
     *                  @OA\Property(property="active", type="boolean"),
     *                  @OA\Property(property="role_id", type="integer"),
     *              ),
     *          ),
     *      ),
     *
     *     @OA\Response(response="200", description="The success response",
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
     * @param UpdateUserRequest $request
     * @param string            $uuid
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, string $uuid): JsonResponse
    {
        $this->service->updateUser(Auth::user(), $uuid, $request->getDTO());
        return $this->apiSimpleSuccessResponse(Response::HTTP_OK);
    }

    /**
     * Deletes a specific user
     *
     * @OA\Delete(
     *      tags={"Users Management"},
     *      path="/v1/users/{uuid}",
     *      description="Deletes a specific user",
     *      security={ "jwt": {} },
     *
     *      @OA\Parameter(name="uuid", in="path", required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *
     *      @OA\Response(response="200", description="The success response",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(type="object",
     *                  @OA\Property(property="success", type="boolean"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(response="400", description="Invalid Request"),
     *      @OA\Response(response="401", description="Unauthorized"),
     *      @OA\Response(response="403", description="Access Denied"),
     *      @OA\Response(response="404", description="Resource not found"),
     * )
     *
     * @param string $uuid
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function delete(string $uuid): JsonResponse
    {
        $this->service->deleteUser($uuid);
        return $this->apiSimpleSuccessResponse(Response::HTTP_OK);
    }
}
