<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Contracts\UserServiceInterface;
use App\Repositories\UserAuthRepository;

class UserService implements UserServiceInterface
{
    public function __construct(protected UserAuthRepository $userRepository)
    {
    }

    public function getProfile($id)
    {
        $user = $this->userRepository->findById($id);
        $user['token'] = getLoginToken();
        return $user;
    }

    public function getUserById($id)
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            throw new \Exception("User not found");
        }
        return $user;
    }

    public function updateProfile(array $data)
    {
        $user = $this->getUserById($data['id']);
        $user->update($data);
        $user->refresh();
        $user['token'] = getLoginToken();

        return $user;
    }

    public function changePassword(array $data)
    {
        $user = $this->getUserById($data['id']);
        if (Hash::check($data['old_password'], $user->password)) {
            $user->update([
                'password' => Hash::make($data['password']),
            ]);

            $user->refresh();
            $user['token'] = getLoginToken();

            return $user;
        }

        throw new \Exception("Old password is incorrect");
    }

    public function updateAvatar($data)
    {
        $user = $this->getUserById($data['id']);
        if ($data['avatar']) {
            $file = storeFiles('users/avatar/', $data['avatar']);
            $user->update(['avatar' => $file]);
        }
        $user->refresh();
        $user['token'] = getLoginToken();
        return $user;
    }
}
