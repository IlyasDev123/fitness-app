<?php

// app/Contracts/AuthServiceInterface.php
namespace App\Contracts;

interface AuthServiceInterface
{
    public function login(array $credentials);
    public function sendOTP(string $email);
    public function register(array $userData);
    public function verifyOTP(array $data);
    public function resetPassword(array $data);
    public function logout();
    public function socialLogin(array $credentials);
}
