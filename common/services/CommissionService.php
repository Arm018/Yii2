<?php

namespace common\services;

use common\models\Balance;
use common\models\User;

class CommissionService
{
    public function handleCommission($user, $book)
    {
        $commission = 0;
        if ($user->referrer_id) {
            $referrer = User::findOne($user->referrer_id);
            if ($referrer) {
                $commission = $book->price * 0.1;

                $referrerBalance = $referrer->getBalance()->one();

                if ($referrerBalance) {
                    $referrerBalance->commission_amount += $commission;
                    $referrerBalance->save();
                }
            }
        }

        return $commission;
    }
}


