<?php

namespace App\Contracts;

interface UserServiceInterface
{
    public function getProfile($id);
    public function updateProfile(array $data);
    public function changePassword(array $data);
    public function updateAvatar($data);
}
