<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\InAppPurchaseHistory;
use App\Models\UserSubscriptionHistory;

class InappPurchaseAppleController extends Controller
{
    /**
     * This function handle the in app purchase webhook
     *
     * @param  mixed $request
     * @return void
     */
    public function inAppSubscribe(Request $request)
    {
        $res = $request->all();

        createDebugLogFile('inApp', 'Apple ====', $res);
        // file_put_contents(public_path('/log.txt'), "Apple:".PHP_EOL.json_encode($res).PHP_EOL.PHP_EOL, FILE_APPEND);

        $jwtPayload = $this->inAppResponseDecode($res["signedPayload"]);

        $transactionPayload = $this->inAppResponseDecode($jwtPayload->data->signedTransactionInfo);
        $renewalPayload = $this->inAppResponseDecode($jwtPayload->data->signedRenewalInfo);

        $subscription = UserSubscription::where('in_app_id', $transactionPayload->originalTransactionId)
            ->latest()->first();

        $subType = isset($jwtPayload->subtype) ? $jwtPayload->subtype : null;

        createDebugLogFile('inApp', 'Txn ====' . json_encode($transactionPayload));

        createDebugLogFile('inApp_subscription', "Trxn ID ==== " . $transactionPayload->originalTransactionId);

        createDebugLogFile('inApp_subscription', "Type: " . $jwtPayload->notificationType . " === Sub: " . $subType);

        switch ($jwtPayload->notificationType) {
            case 'SUBSCRIBED':
                // sub types => INITIAL_BUY, RESUBSCRIBE
                $this->updateSubscriptionStatus($subscription, $transactionPayload, $jwtPayload);
                break;
            case 'DID_RENEW' && ($subType == ''):

                $this->updateSubscriptionStatus($subscription, $transactionPayload, $jwtPayload);
                break;
            case 'DID_RENEW' && ($subType == 'BILLING_RECOVERY'):

                $this->updateSubscriptionStatus($subscription, $transactionPayload, $jwtPayload);
                break;
            case 'DID_CHANGE_RENEWAL_STATUS' && ($subType == 'AUTO_RENEW_ENABLED'):

                $this->updateSubscriptionStatus($subscription, $transactionPayload, $jwtPayload);
                break;
            case 'DID_CHANGE_RENEWAL_STATUS' && ($subType == 'AUTO_RENEW_DISABLED'):

                $this->updateSubscriptionStatus($subscription, $transactionPayload, $jwtPayload);
                break;
            case 'DID_FAIL_TO_RENEW' && ($subType == 'GRACE_PERIOD'):

                $this->updateSubscriptionStatus($subscription, $transactionPayload, $jwtPayload);
                break;
            case 'DID_FAIL_TO_RENEW' && ($subType == ''):

                $this->updateSubscriptionStatus($subscription, $transactionPayload, $jwtPayload);
                break;
            case 'EXPIRED':
                // sub types => VOLUNTARY, BILLING_RETRY, PRICE_INCREASE, PRODUCT_NOT_FOR_SALE
                $this->updateSubscriptionStatus($subscription, $transactionPayload, $jwtPayload);
                break;
            default:
                break;
        }

        return $this->storeDataInAppHistoryTable($jwtPayload, $res, $transactionPayload, $renewalPayload, $subscription);
    }

    public function inAppResponseDecode($response)
    {
        $tokenParts = explode(".", $response);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);
        return $jwtPayload;
    }

    public function storeDataInAppHistoryTable($jwtPayload, $res, $transactionPayload, $renewalPayload, $subscription)
    {
        if (!isset($subscription->user_id)) {
            createDebugLogFile('inApp_subscription', "User Id not found.");
            return "User Id not found";
        }

        $inAppHistory = InAppPurchaseHistory::create([
            "transaction_id" => $transactionPayload->originalTransactionId,
            "notification_type" => $jwtPayload->notificationType,
            "in_app_response" => $res["signedPayload"],
            "sub_type" => $jwtPayload->subtype ?? "",
            "transaction_info" => json_encode($transactionPayload),
            "other_info" => json_encode($renewalPayload),
            "user_id" => $subscription->user_id ?? ""
        ]);

        createDebugLogFile('inApp_subscription', "In Ap Purchase History.");
        return $inAppHistory;
    }

    public function updateSubscriptionStatus($payload, $transactionPayload, $jwtPayload)
    {
        $subscribeUser = UserSubscription::where('user_id', $payload->user_id)->first();
        if (!$subscribeUser) {
            createDebugLogFile('inApp_subscription', "Subscription User Id not found.");
            return false;
        }

        $notificationType = $jwtPayload->notificationType;
        $subType = isset($jwtPayload->subtype) ? $jwtPayload->subtype : null;

        // $expireDate = date('Y-m-d', ); # in milliseconds from apple

        if ($notificationType == 'EXPIRED') { # this is for sandbox testing
            $expireDate = date('Y-m-d', strtotime('-1 day', $transactionPayload->expiresDate / 1000));
        } else {
            $expireDate = date('Y-m-d', $transactionPayload->expiresDate / 1000);
        }

        $update = [
            "expiry_date" => $expireDate,
            // "status_type" => $notificationType, // for track purpose
        ];

        if ($notificationType == 'EXPIRED' || ($notificationType == 'DID_CHANGE_RENEWAL_STATUS' && $subType == 'AUTO_RENEW_DISABLED')) {
            $update['is_active'] = 0;
            User::find($payload->user_id)->update(['is_premium' => 0]);
        } else {
            $update['is_active'] = 1;
        }

        $subscribeUser->update($update);

        // $subscriptionHistory = SubscribeUserHistory::where('in_app_id', $transactionPayload->originalTransactionId)->latest()->first();
        UserSubscriptionHistory::create([
            "user_id" => $payload->user_id,
            "package_id" => $subscribeUser->package_id,
            "in_app_id" => $transactionPayload->originalTransactionId,
            "in_app_type" => 'apple',
            "inapp_response" => json_encode($transactionPayload),
            "created_at" => $subscribeUser->purchase_date,
            "expiry_date" => $expireDate,
            'user_subscription_id' => $subscribeUser->id,
        ]);

        createDebugLogFile('inApp_subscription', "Renew subscription success");
        return true;
        // return $data;
    }
}
