<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function userRegistration(UserRegistrationRequest $request)
    {
        $newUser = $this->authService->userRegistration($request);
        if (!$newUser) {

            return $this->response('error in creating user', $newUser, false, Response::HTTP_UNPROCESSABLE_ENTITY);

        }

        return $this->response('User Created Successfully', $newUser, true, Response::HTTP_CREATED);

    }

    public function login(Request $request)
    {
        $login = $this->authService->login($request);
        if (!$login) {

            return $this->response('Invalid login details',$login, false, Response::HTTP_UNPROCESSABLE_ENTITY);

        }
        return $this->response('User logged in successfully', $login, true, Response::HTTP_ACCEPTED);
    }

    public function logout(Request $request)
    {
        $user = $this->authService->logout($request);

        return $this->response('User logged out successfully', $user, true, Response::HTTP_OK);
    }

}
