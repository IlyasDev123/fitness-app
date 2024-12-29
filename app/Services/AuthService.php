<?php
// app/Services/AuthService.php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Jobs\SendEmailNotification;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Contracts\AuthServiceInterface;;

use App\Repositories\UserAuthRepository;

class AuthService implements AuthServiceInterface
{

    public function __construct(protected UserAuthRepository $userRepository)
    {
    }

    public function register(array $userData)
    {
        $userData['password'] = Hash::make($userData['password']);
        $user = $this->userRepository->create($userData);
        $this->createDevice($user, $userData);
        $this->storeOtp($user);

        return $user;
    }

    public function login(array $credentials)
    {
        if (auth()->attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $user = Auth::user();
            if (!$user->email_verified_at) {
                return $user->only('is_verified_email');
            }

            $this->createDevice($user, $credentials);
            $tokenResult = $user->createToken('User Personal Access Token');
            $user['token'] = $tokenResult->accessToken;

            return $user;
        }
        throw new \Exception('Invalid credentials');
    }

    public function socialLogin(array $credentials)
    {
        DB::beginTransaction();
        $user = $this->userRepository->verifiedUserFindBy('email', $credentials['email']);
        if (!$user) {
            $user = $this->userRepository->create([
                'name' => $credentials['name'],
                'email' => $credentials['email'],
                'email_verified_at' => now(),
                "timezone" => $credentials['timezone'],
            ]);
            $this->userRepository->storeSocialToken($user, $credentials);
        }
        $this->createDevice($user, $credentials);
        $tokenResult = $user->createToken('User Personal Access Token');
        $user['token'] = $tokenResult->accessToken;
        DB::commit();
        return $user;
    }

    public function sendOTP(string $email)
    {
        $user = $this->userRepository->findBy("email", $email);
        if (!$user) {
            throw new \Exception('User not found');
        }

        return $this->storeOtp($user);
    }

    public function storeOtp($user)
    {
        $code = mt_rand(1000, 9999);
        $user->otp()->updateOrCreate([
            'user_id' => $user->id,
        ], [
            'code' => $code,
        ]);
        $data = [
            "email" => $user->email,
            "otp" => $code,
            "subject" => "Sent OTP",
            "message" => "Your OTP is $code",
            "view" => "emails.otp",
        ];
        SendEmailNotification::dispatch($data);

        return $code;
    }



    public function verifyOTP(array $data)
    {
        $user = $this->userRepository->findBy("email", $data['email']);
        if (!$user) {
            throw new \Exception('User not found');
        }
        $currentTime = now()->subMinutes(30);
        $otp = $user->otp()->where('code', $data['code'])->where('updated_at', '>=', $currentTime)->first();
        if (!$otp) {
            throw new \Exception('Invalid OTP');
        }
        $user->update([
            'email_verified_at' => now(),
        ]);

        $otp->delete();

        return $user;
    }

    public function resetPassword(array $data)
    {
        $user = $this->userRepository->findBy("email", $data['email']);
        if (!$user) {
            throw new \Exception('User not found');
        }
        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }

    // passworde reset

    public function logout()
    {
        $user = Auth::user();
        $user->device()->delete();
        $user->tokens()->delete();
        return null;
    }

    public function createDevice($user, array $data)
    {
        return $user->device()->create([
            'fcm_token' => $data['fcm_token'],
            'device_id' => $data['device_id'],
        ]);
    }

    public function deleteUser()
    {
        $user = Auth::user();
        $user->delete();
        return null;
    }
}
