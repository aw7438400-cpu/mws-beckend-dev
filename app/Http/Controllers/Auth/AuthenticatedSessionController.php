<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Http\Resources\Auth\Login\UserAuthResource;
use App\Http\Resources\Auth\Login\AuthenticatedSessionResource;

class AuthenticatedSessionController extends Controller
{
    protected $authenticatedSessionService;

    public function __construct(AuthenticatedSessionService $authenticatedSessionService)
    {
        $this->authenticatedSessionService = $authenticatedSessionService;
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $user = $this->authenticatedSessionService->store($request);

        return $this->authenticatedSessionService->success(new AuthenticatedSessionResource($user), 200, 'logged in successfully');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->authenticatedSessionService->success(null, 200, 'logged out successfully');
    }

    /**
     * Refresh the access token using the refresh token.
     */
    public function refreshToken(RefreshTokenRequest $request)
    {
        $data = $request->validated();

        $user = $this->authenticatedSessionService->refresh($data);

        return $this->authenticatedSessionService->success(new AuthenticatedSessionResource($user), 200, 'Refresh Token Successfully');
    }

    /**
     * Check if the user is authenticated.
     */
    public function authCheck()
    {
        return $this->authenticatedSessionService->success(new UserAuthResource(Auth::user()));
    }
}
