<?php

namespace App\Http\Controllers\Apis\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Contracts\AuthServiceInterface;
use App\Http\Requests\Auth\loginRequest;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Requests\Auth\SocialLoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;

class AuthController extends Controller
{
    public function __construct(protected AuthServiceInterface $authService)
    {
    }

    public function login(loginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password', 'timezone', 'device_id', 'fcm_token');
            $data = $this->authService->login($credentials);

            return sendSuccess($data, 'Login successfully');
        } catch (\Throwable $th) {
            return sendError($th->getMessage());
        }
    }

    public function sendOTP(SendOtpRequest $request)
    {
        try {
            $email = $request->input('email');
            $data = $this->authService->sendOTP($email);

            return sendSuccess(null, 'OTP sent successfully');
        } catch (\Throwable $th) {
            return sendError("Something went wrong. Please try again later.");
        }
    }

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $userData = $request->only('name', 'email', 'password', 'device_id', 'fcm_token', 'timezone');
            $data = $this->authService->register($userData);
            DB::commit();
            return  sendSuccess($data, 'Registered successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendError("Something went wrong. Please try again later." . $th->getMessage());
        }
    }

    public function verifyOTP(VerifyOtpRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->only('email', 'code');
            $data = $this->authService->verifyOTP($data);
            DB::commit();
            return sendSuccess($data, 'OTP verified successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendError($th->getMessage());
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $data = $request->only('email', 'password');
            $data = $this->authService->resetPassword($data);
            return sendSuccess($data, 'Password reset successfully');
        } catch (\Throwable $th) {
            return sendError("Something went wrong. Please try again later.");
        }
    }

    public function logout()
    {
        try {
            $data = $this->authService->logout();
            return sendSuccess($data, 'Logout successfully');
        } catch (\Throwable $th) {
            return sendError("Something went wrong. Please try again later.");
        }
    }

    public function socialLogin(SocialLoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'token', 'timezone', 'device_id', 'fcm_token', 'type', 'name');
            $data = $this->authService->socialLogin($credentials);
            return sendSuccess($data, 'Login successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendError("Something went wrong. Please try again later." . $th->getMessage());
        }
    }

    public function verifyEmail(VerifyOtpRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->only('email', 'code');
            $user = $this->authService->verifyOTP($data);
            $tokenResult = $user->createToken('User Personal Access Token');
            $user['token'] = $tokenResult->accessToken;
            DB::commit();

            return sendSuccess($user, 'Account verified successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendError($th->getMessage());
        }
    }

    public function deleteUser()
    {
        try {
            $data = $this->authService->deleteUser();
            return sendSuccess($data, 'User deleted successfully');
        } catch (\Throwable $th) {
            return sendError("Something went wrong. Please try again later.");
        }
    }
}
