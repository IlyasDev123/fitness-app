<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{

    public function getSubscriptions()
    {
        $searchItem = request()->query('search');
        $subscriptions = UserSubscription::with('user', 'package')->when($searchItem, fn ($q) => $q->search($searchItem))
            ->latest()->paginate(prePageLimit());
        return sendSuccess($subscriptions, "success");
    }

    public function getSubscriptionDetail($id)
    {
        $subscription = UserSubscription::with(['user', 'package', 'userSubscriptionHistory.package'])->find($id);
        return sendSuccess($subscription, "success");
    }
}
