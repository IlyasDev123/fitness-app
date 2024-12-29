<?php
// app/Services/AuthService.php
namespace App\Repositories;

use App\Models\User;

class UserAuthRepository
{

    /**
     * @param string $id
     * @return User|null
     */
    public function findById($id): ?User
    {
        return User::find($id);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findBy($key, $value): ?User
    {
        return User::where($key, $value)->first();
    }

    /**
     * @param array<string, mixed> $userData
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    // firstOrCreate method
    public function firstOrCreate(array $data): User
    {
        return User::firstOrCreate($data);
    }

    // updateOrCreate method
    public function updateOrCreate(array $data, array $condition): User
    {
        return User::updateOrCreate($data, $condition);
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function delete(int $userId): bool
    {
        return User::where('id', $userId)->delete();
    }


    public function storeSocialToken($user, array $data)
    {
        $user->socialLogin()->updateOrCreate([
            'token' => $data['token'],
        ], [
            'type' => $data['type']
        ]);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function verifiedUserFindBy($key, $value): ?User
    {
        return User::where($key, $value)->whereNotNull('email_verified_at')->first();
    }
}
