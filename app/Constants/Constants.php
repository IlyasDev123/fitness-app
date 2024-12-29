<?php

namespace App\Constants;

class Constants
{
    const DEFAULT_PAGINATION = 10;

    const WORKOUT_STATUS = [
        'active' => 1,
        'inactive' => 0,
    ];

    const WORKOUT_TYPE = [
        'cardio' => 1,
        'strength' => 2,
    ];

    const USER_STATUS = [
        'active' => 1,
        'inactive' => 0,
    ];

    const STATUS = [
        'active' => 1,
        'inactive' => 0,
    ];

    const CATEGORY_TYPE = [
        'workout' => 1,
        'insight' => 2,
    ];

    # playstore inapp subscription types
    const SUBSCRIPTION_RECOVERED = 1;
    const SUBSCRIPTION_RENEWED = 2;
    const SUBSCRIPTION_CANCELED = 3;
    const SUBSCRIPTION_PURCHASED = 4;
    const SUBSCRIPTION_ON_HOLD = 5;
    const SUBSCRIPTION_IN_GRACE_PERIOD = 6;
    const SUBSCRIPTION_RESTARTED = 7;
    const SUBSCRIPTION_PRICE_CHANGE_CONFIRMED = 8;
    const SUBSCRIPTION_DEFERRED = 9;
    const SUBSCRIPTION_PAUSED = 10;
    const SUBSCRIPTION_PAUSE_SCHEDULE_CHANGED = 11;
    const SUBSCRIPTION_REVOKED = 12;
    const SUBSCRIPTION_EXPIRED = 13;
    const SUBSCRIPTION_PENDING = 14;
    const SUBSCRIPTION_UNKNOWN = 15;
}
