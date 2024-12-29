<?php

namespace App\Contracts;

use App\Models\Package;

interface SubscriptionServiceInterface
{
    public function getActivePackages();
    public function subscribe(array $data);
    public function getPackageById(int $id);
    public function activeSubscription(string $inappPackageId);
    public function addPackage(array $data);
    public function getActiveSubscription();
}
