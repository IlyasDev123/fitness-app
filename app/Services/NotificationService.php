<?php

namespace App\Services;

use App\Models\Notification;
use App\Http\Traits\FirebaseNotificationTrait;

class NotificationService
{
    use FirebaseNotificationTrait;
    public function sendNotification($data)
    {
        $this->sendFirebaseNotification($data['device_token'], $data['title'], $data['body'], $data['data']);
        return $this->create($data);
    }

    public function create($data)
    {
        return Notification::create($data);
    }

    public function getNotifications()
    {
        return Notification::where('user_id', auth()->id())->latest()->get();
    }

    public function notificationCount()
    {
        return Notification::where('user_id', auth()->id())->where('is_read', 0)->count();
    }

    public function markAsRead()
    {
        return Notification::where('user_id', auth()->id())->update(['is_read' => 1]);
    }
}
