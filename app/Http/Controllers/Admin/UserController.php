<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\Admin\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Index\UserResource;
use App\Http\Requests\Admin\Index\IndexUserRequest;
use App\Http\Requests\Admin\Store\StoreUserRequest;
use App\Http\Requests\Admin\Update\UpdateUserRequest;
use App\Http\Resources\Admin\Detail\DetailUserResource;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware(['permission:index user'], ['only' => ['index']]);
        $this->middleware(['permission:get user'], ['only' => ['get']]);
        $this->middleware(['permission:create user'], ['only' => ['store']]);
        $this->middleware(['permission:update user'], ['only' => ['update']]);
        $this->middleware(['permission:delete user'], ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     */
    
    public function index(IndexUserRequest $indexUserRequest)
    {
        $query = $indexUserRequest->validated();

        $result = $this->userService->searchUser(['userDetail'], 10, $query['search'] ?? null);

        return $this->userService->successPaginate(UserResource::collection($result), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $storeUserRequest)
    {
        $data = $storeUserRequest->validated();

        $result = $this->userService->registerUser($data);

        return $this->userService->success(new DetailUserResource($result), 200, 'Created User Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function get(string $uuid)
    {
        $result = $this->userService->findByUuidWithRelation($uuid, ['userDetail']);

        return $this->userService->success(new DetailUserResource($result), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $updateUserRequest, string $uuid)
    {
        $data = $updateUserRequest->validated();

        $result = $this->userService->updateUser($uuid, $data);

        return $this->userService->success(new DetailUserResource($result), 200, 'Updated User Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $result = $this->userService->destroyByUuid($uuid);

        return $this->userService->success('', 200, 'Deleted User Successfully');
    }
}
