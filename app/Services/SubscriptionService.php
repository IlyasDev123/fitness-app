<?php

namespace App\Services;

use App\Models\Package;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;
use App\Models\UserSubscriptionHistory;
use App\Contracts\SubscriptionServiceInterface;

class SubscriptionService implements SubscriptionServiceInterface
{

    public function getActivePackages()
    {
        return Package::where('is_active', true)->get();
    }

    public function getPackageById(int $id)
    {
        return Package::find($id);
    }

    public function addPackage(array $data)
    {
        return Package::create($data);
    }

    public function activeSubscription(string $inappTransactionId)
    {
        return UserSubscription::where(['user_id' => auth()->id(), 'in_app_id' => $inappTransactionId])->whereDate('expire_date', '>', now())->latest()->first();
    }

    public function createSubscription(array $data)
    {
        return UserSubscription::updateOrCreate([
            'user_id' => auth()->id(),
        ], [
            'package_id' => $data['package_id'],
            'in_app_id' => $data['in_app_id'],
            'expire_date' => $data['expire_date'],
            'is_active' => true,
        ]);
    }

    public function createSubscriptionHistory(array $data, int $subscribeId)
    {
        return UserSubscriptionHistory::create([
            'user_subscription_id' => $subscribeId,
            'in_app_id' => $data['in_app_id'],
            'inapp_response' => json_decode($data['inapp_response']) ?? null,
            'expire_date' => $data['expire_date'],
            'user_id' => auth()->id(),
            'in_app_type' => $data['in_app_type'],
            'package_id' => $data['package_id'],
        ]);
    }

    public function subscribe(array $data)
    {
        $subscribe = $this->activeSubscription($data['in_app_id']);
        if ($subscribe) {
            throw new \Exception('You already have an active subscription');
        }
        DB::transaction(function () use ($data) {
            $subscribe = $this->createSubscription($data);
            $this->createSubscriptionHistory($data, $subscribe->id);
            auth()->user()->update([
                'is_premium' => true
            ]);
        });

        return auth()->user()->fresh();
    }

    public function getActiveSubscription()
    {
        return UserSubscription::with('package')->where(['user_id' => auth()->id()])->whereDate('expire_date', '>', now())->latest()->first();
    }
}
