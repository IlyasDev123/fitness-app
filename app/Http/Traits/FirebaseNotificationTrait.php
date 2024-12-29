<?php

// app/Traits/FirebaseNotificationTrait.php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Http;

trait FirebaseNotificationTrait
{
    /**
     * Send a Firebase notification.
     *
     * @param string $deviceToken The FCM device token to send the notification to.
     * @param string $title       The title of the notification.
     * @param string $body        The body of the notification.
     * @param array  $data        Additional data to include in the notification payload.
     *
     * @return bool
     */
    public function sendFirebaseNotification($deviceToken, $title, $body, $data = [])
    {
        try {
            $serverKey = config('firebase.server_key');

            $response = Http::post(
                'https://fcm.googleapis.com/fcm/send',
                [
                    'to' => $deviceToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $data,
                ],
                [
                    'Authorization' => 'key=' . $serverKey,
                    'Content-Type' => 'application/json',
                ]
            );

            return $response->successful();
        } catch (\Exception $e) {
            // Handle the exception as needed (log, report, etc.)
            return false;
        }
    }
}
