<?php

namespace App\Http\Controllers\Apis\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    public function getNotifications()
    {
        $data = $this->notificationService->getNotifications();
        return sendSuccess($data, 'Notifications fetched successfully');
    }

    public function sendNotification(Request $request)
    {
        $data = $this->notificationService->sendNotification($request->all());
        return sendSuccess($data, 'Notification sent successfully');
    }

    public function notificationCount()
    {
        $data = $this->notificationService->notificationCount();
        return sendSuccess($data, 'Notification count fetched successfully');
    }

    public function markAsRead()
    {
        $data = $this->notificationService->markAsRead();
        return sendSuccess($data, 'Notification marked as read successfully');
    }
}
