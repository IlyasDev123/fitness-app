<?php

namespace App\Http\Controllers\Apis\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\UserServiceInterface;
use App\Http\Requests\User\UpdateAvatarRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\ChangePasswordRequest;

class UserController extends Controller
{
    public function __construct(protected UserServiceInterface $userService)
    {
    }

    public function getProfile($id = null)
    {
        $user = $this->userService->getProfile(auth()->id());
        return sendSuccess($user, 'Success');
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $data = $request->only('name');
            $data['id'] = auth()->id();
            $data = $this->userService->updateProfile($data);

            return sendSuccess($data, 'Updated successfully');
        } catch (\Throwable $th) {
            return sendError("Something went wrong. Please try again later.");
        }
    }

    public function updateAvatar(UpdateAvatarRequest $request)
    {
        try {
            $data = $request->only('avatar');
            $data['id'] = auth()->id();
            $data = $this->userService->updateAvatar($data);

            return sendSuccess($data, 'Updated successfully');
        } catch (\Throwable $th) {
            return sendError("Something went wrong. Please try again later." . $th->getMessage());
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $data = $request->only('old_password', 'password');
            $data['id'] = auth()->id();
            $data = $this->userService->changePassword($data);

            return sendSuccess($data, 'Password updated successfully');
        } catch (\Throwable $th) {
            return sendError($th->getMessage());
        }
    }
}
