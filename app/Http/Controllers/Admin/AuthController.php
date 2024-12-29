<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (Auth::guard('adminauth')->attempt($credentials)) {
            $user = Auth::guard('adminauth')->user();
            $tokenResult = $user->createToken('User Personal Access Token');
            $user['token'] = $tokenResult->accessToken;

            return sendSuccess($user, 'Login successfully');
        }

        return sendErrorResponse('Invalid credentials');
    }

    public function logout()
    {
        $user = Auth::guard('admin')->user();
        $user->token()->revoke();
        return sendSuccess(null, 'Logout successfully');
    }
}
