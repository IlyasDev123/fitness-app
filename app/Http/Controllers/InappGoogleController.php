<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Constants\Constants;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Models\InAppPurchaseHistory;
use App\Models\UserSubscriptionHistory;

class InappGoogleController extends Controller
{
    public function inAppSubscribe(Request $request)
    {
        $res = $request->all();
        try {
            //code...

            $payload = json_decode(base64_decode($res['message']['data']), true);
            $res['message']['data'] = $payload;


            if (isset($payload['subscriptionNotification'])) {

                $transactionID = $payload['subscriptionNotification']['purchaseToken'];
                $notificationType = $payload['subscriptionNotification']['notificationType'];

                createDebugLogFile('purchaseToken', $transactionID);

                switch ($notificationType) {
                    case Constants::SUBSCRIPTION_RECOVERED:

                        $this->updateSubscriptionStatus($transactionID, $res, "SUBSCRIPTION_RECOVERED");
                        break;
                    case Constants::SUBSCRIPTION_RENEWED:

                        $this->updateSubscriptionStatus($transactionID, $res, "SUBSCRIPTION_RENEWED");
                        break;
                        // case Constants::SUBSCRIPTION_CANCELED:

                        //     $this->subscriptionStatusTrack($transactionID, $res, "SUBSCRIPTION_CANCELED");
                        //     break;
                        // case Constants::SUBSCRIPTION_PURCHASED:
                        //     $this->updateSubscriptionStatus($transactionID, $res, "SUBSCRIPTION_PURCHASED");
                        //     break;
                    case Constants::SUBSCRIPTION_ON_HOLD:

                        $this->subscriptionStatusTrack($transactionID, $res, "SUBSCRIPTION_ON_HOLD");
                        break;
                    case Constants::SUBSCRIPTION_IN_GRACE_PERIOD:

                        $this->subscriptionStatusTrack($transactionID, $res, "SUBSCRIPTION_IN_GRACE_PERIOD");
                        break;
                    case Constants::SUBSCRIPTION_RESTARTED:

                        $this->subscriptionStatusTrack($transactionID, $res, "SUBSCRIPTION_RESTARTED");
                        break;
                    case Constants::SUBSCRIPTION_PRICE_CHANGE_CONFIRMED:

                        $this->subscriptionStatusTrack($transactionID, $res, "SUBSCRIPTION_PRICE_CHANGE_CONFIRMED");
                        break;
                    case Constants::SUBSCRIPTION_DEFERRED:

                        $this->subscriptionStatusTrack($transactionID, $res, "SUBSCRIPTION_DEFERRED");
                        break;
                    case Constants::SUBSCRIPTION_PAUSED:

                        $this->subscriptionStatusTrack($transactionID, $res, "SUBSCRIPTION_PAUSED");
                        break;
                    case Constants::SUBSCRIPTION_PAUSE_SCHEDULE_CHANGED:

                        $this->subscriptionStatusTrack($transactionID, $res, "SUBSCRIPTION_PAUSE_SCHEDULE_CHANGED");
                        break;
                    case Constants::SUBSCRIPTION_REVOKED:

                        $this->stopSubscription($transactionID, $res, "SUBSCRIPTION_REVOKED");
                        break;
                    case Constants::SUBSCRIPTION_EXPIRED:
                        $this->subscriptionExpiredStatus($transactionID, $res, "SUBSCRIPTION_EXPIRED");
                        break;
                    default:
                        createDebugLogFile('android', "subscription failed.");
                        break;
                }

                // $this->storeDataInAppHistoryTable($payload, $res, $transactionID);
            }

            return response(true, 200);
        } catch (\Throwable $th) {
            createDebugLogFile('android-error', $th->getMessage());
        }
    }

    public function updateSubscriptionStatus($transactionId, $transactionPayload, $notifiType)
    {
        try {
            createDebugLogFile('android-renew', "Type: " . $transactionId . " " . $notifiType);
            $subscribeUser = UserSubscription::where('in_app_id', $transactionId)->with('package')->latest()->first();
            createDebugLogFile('android-packages-list', "Type: " . $subscribeUser->package->duration);

            if (!$subscribeUser) {
                createDebugLogFile('android-renew-' . $notifiType, "Subscription User not found. (1)");
                return response(true, 200);
            }

            if ($subscribeUser->package->duration == '6 Months') {
                $expireDate = Carbon::now()->addMonth(6)->toDateString();
            } else if ($subscribeUser->package->duration == 'Yearly') {
                $expireDate = Carbon::now()->addYear(1)->toDateString();
            } else if ($subscribeUser->package->duration == 'Monthly') {
                $expireDate = Carbon::now()->addMonth(1)->toDateString();
            } else if ($subscribeUser->package->duration == '3 Months') {
                $expireDate = Carbon::now()->addMonth(3)->toDateString();
            } else {
                $expireDate = Carbon::now()->addMonth(1)->toDateString();
            }

            $subscribeUser->update([
                "expire_date" => $expireDate,
            ]);

            $inAppHistory = UserSubscriptionHistory::create([
                "user_id" => $subscribeUser->user_id,
                "package_id" => $subscribeUser->package_id,
                'user_subscription_id' => $subscribeUser->id,
                "in_app_id" => $transactionId,
                "in_app_type" => 'google',
                "inapp_response" => json_encode($transactionPayload),
                "created_at" => $subscribeUser->purchase_date,
                "expire_date" => $expireDate,
            ]);

            createDebugLogFile('inApp_subscription', "Renew subscription success");

            return response(true, 200);
        } catch (\Throwable $th) {
            createDebugLogFile('android-error-renew', $th->getMessage());
        }
    }

    public function stopSubscription($transactionId, $transactionPayload, $notifiType)
    {
        createDebugLogFile('android', "Type: " . $notifiType);

        $subscribeUser = UserSubscription::where('in_app_id', $transactionId)->latest()->first();

        if (!$subscribeUser) {
            createDebugLogFile('android', "Subscription User not found. (2)");

            return response(true, 200);
        }

        // $type = $transactionPayload['message']['data']['subscriptionNotification']['notificationType'];

        // need to update this to update expiry date
        $expireDate = Carbon::now()->subDays(1)->toDateString();
        $subscribeUser->update([
            "expiry_date" => $expireDate,
            'is_active' => 0,
        ]);

        User::find($subscribeUser->user_id)->update(['is_premium' => 0]);

        createDebugLogFile('inApp_subscription', "Removed subscription success");

        return response(true, 200);
    }

    public function subscriptionExpiredStatus($transactionId, $transactionPayload, $notifiType)
    {
        createDebugLogFile('android', "Type: " . $notifiType);


        $subscribeUser = UserSubscription::where('in_app_id', $transactionId)->latest()->first();


        if (!$subscribeUser) {
            createDebugLogFile('android', "Subscription User not found. (3)");

            return response(true, 200);
        }
        User::find($subscribeUser->user_id)->update(['is_premium' => 0]);
        $expireDate = Carbon::now()->subDays(1)->toDateString();
        $subscribeUser->update([
            "expire_date" => $expireDate,
            'is_active' => 0,
        ]);



        createDebugLogFile('inApp_subscription', "Removed subscription success");

        return response(true, 200);
    }

    public function subscriptionStatusTrack($transactionId, $transactionPayload, $notifiType)
    {
        createDebugLogFile('android-status', "Type: " . $notifiType);

        $subscribeUser = UserSubscription::where('in_app_id', $transactionId)->latest()->first();

        if (!$subscribeUser) {
            createDebugLogFile('android-status-cjek', "subscription status log failed. (4)");
            return response(true, 200);
        }

        if ($notifiType == Constants::SUBSCRIPTION_CANCELED || Constants::SUBSCRIPTION_ON_HOLD) {
            $subscribeUser->update([
                'is_active' => 0,
            ]);
            User::find($subscribeUser->user_id)->update(['is_premium' => 0]);
        } elseif ($notifiType == Constants::SUBSCRIPTION_RESTARTED) {
            $subscribeUser->update([
                'is_active' => 1,
            ]);
            User::find($subscribeUser->user_id)->update(['is_premium' => 1]);
        }

        createDebugLogFile('inApp_subscription', "Subscription status.");

        return response(true, 200);
    }



    public function storeDataInAppHistoryTable($payload, $res, $transactionId)
    {
        try {
            $subscribeUser = UserSubscription::where('in_app_id', $transactionId)->latest()->first();

            if (!$subscribeUser) {
                createDebugLogFile('android-history', "Subscription User not found. (5)");
                return response(true, 200);
            }

            $inAppHistory = UserSubscriptionHistory::create([
                "user_id" => $subscribeUser->user_id,
                "package_id" => $subscribeUser->package_id,
                'user_subscription_id' => $subscribeUser->id,
                "in_app_id" => $transactionId,
                "in_app_type" => 'google',
                "inapp_response" => json_encode($payload),
                "created_at" => $subscribeUser->purchase_date,
                "expire_date" => $$subscribeUser->expire_date,
            ]);

            createDebugLogFile('inApp_subscription-history-add', "In Ap Purchase History Added success fully.");

            return response(true, 200);
        } catch (\Throwable $th) {
            createDebugLogFile('android-error-inapp-history-table', $th->getMessage());
        }
    }

    // https://cloud.google.com/pubsub/docs/push (status codes)
    // https://developer.android.com/google/play/billing/subscriptions#resubscribe
    // https://medium.com/androiddevelopers/preparing-your-apps-for-the-latest-features-in-google-plays-billing-system-210ed5e50eaa
}
