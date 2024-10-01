<?php

namespace frontend\controllers;

use common\models\Balance;
use Yii;
use yii\web\Controller;

class UserController extends Controller
{
    public function actionProfile()
    {
        $user = Yii::$app->user->identity;
        $balance = Balance::findOne(['user_id' => $user->id]);
        return $this->render('profile', [
            'user' => $user,
            'balance' => $balance
        ]);
    }

}