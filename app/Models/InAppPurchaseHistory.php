<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InAppPurchaseHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        "transaction_id",
        "notification_type",
        "in_app_response",
        "sub_type",
        "transaction_info",
        "other_info",
        "user_id"
    ];
}
