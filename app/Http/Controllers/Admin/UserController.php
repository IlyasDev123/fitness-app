<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\UpdateStatusRequest;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Http\Requests\Admin\UpdatePasswordRequest;

class UserController extends Controller
{

    public function getProfile()
    {
        $user = auth('admin')->user();
        return sendSuccess($user, 'User profile');
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $admin = Admin::find(auth('admin')->id());
            $admin->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            return sendSuccess($admin->refresh(), 'Update profile successfully');
        } catch (\Throwable $th) {
            return sendErrorResponse($th->getMessage());
        }
    }

    public function passwordReset(UpdatePasswordRequest $request)
    {
        $user = auth('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            return sendErrorResponse('Old password is incorrect');
        }
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return sendSuccess(null, 'Reset password successfully');
    }

    public function getUsers()
    {
        $search = request('search');
        $users = User::when(
            $search,
            fn ($query, $search) => $query->search($search)
        )->paginate(prePageLimit());
        return sendSuccess($users, 'success');
    }

    public function updateStatus(UpdateStatusRequest $request)
    {
        try {
            $user = User::find($request->user_id);
            $user->update([
                'status' => $request->status,
            ]);

            return sendSuccess(null, 'User has been blocked successfully');
        } catch (\Throwable $th) {
            return sendErrorResponse('Something went wrong! Please try again.');
        }
    }

    public function getUser($id)
    {
        $user = User::withCount('userWorkouts')->find($id);
        return sendSuccess($user, 'success');
    }
}
