<?php
namespace App\Services;

use App\Models\User;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthService
{

    use RespondsWithHttpStatus;

    public function userRegistration($request)
    {
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->password = Hash::make($request->password);
        $user->save();

        return $user;
    }

    public function login($request)
    {
        $request->validate([
            'email' => 'required',
            'password' => ['required'],
        ]);
        if (!Auth::attempt($request->only('email', 'password'))) {

            return false;
        }
        $user = auth()->user();
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->access_token = $token;

        return $user;
    }

    public function logout($request)
    {
        $request->validate([
            'email' => 'required',
        ]);
        $user = User::where('email', $request['email'])->firstOrFail();
        $user->tokens()->delete();

        return $user;

    }

}
