<?php

namespace Modules\User\Models\Wallet;

use App\User;
use Modules\Booking\Models\Payment;

class DepositPayment extends Payment
{
    public static function countPending()
    {
        return parent::query()->where("object_model", "wallet_deposit")->where("status", 'processing')->count("id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'object_id')->withDefault();
    }
}
