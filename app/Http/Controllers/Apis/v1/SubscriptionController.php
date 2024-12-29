<?php

namespace App\Http\Controllers\Apis\v1;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\PackageResource;
use App\Http\Resources\UserSubscribeResourace;
use App\Contracts\SubscriptionServiceInterface;
use App\Http\Requests\Package\AddPackageRequest;
use App\Http\Requests\Package\BuySubscriptionRequest;

class SubscriptionController extends Controller
{
    public function __construct(protected SubscriptionServiceInterface $subscriptionService)
    {
    }

    public function getActivePackages()
    {
        $data = $this->subscriptionService->getActivePackages();

        return sendSuccess($data, 'Packages fetched successfully');
    }

    public function getPackageById($id)
    {
        $data = $this->subscriptionService->getPackageById($id);
        $data = PackageResource::make($data);
        return sendSuccess($data, 'Package fetched successfully');
    }

    public function addPackage(AddPackageRequest $request)
    {
        $data = $request->only('name', 'inapp_package_id', 'price', 'duration', 'is_active');
        $data = $this->subscriptionService->addPackage($data);
        return sendSuccess($data, 'Package added successfully');
    }

    public function subscribe(BuySubscriptionRequest $reqest)
    {
        try {
            $data = $this->subscriptionService->subscribe($reqest->all());
            return sendSuccess($data, 'Subscribed successful');
        } catch (\Throwable $th) {
            return sendError("Something went wrong, please try again later." . $th->getMessage());
        }
    }

    public function getActiveSubscription()
    {
        $data = $this->subscriptionService->getActiveSubscription();
        $data = $data ? UserSubscribeResourace::make($data) : null;
        return sendSuccess($data, 'Subscriptions fetched successfully');
    }
}
