<?php

namespace frontend\controllers;

use common\models\Balance;
use common\models\UserBook;
use common\models\Withdrawal;
use Yii;
use yii\web\Controller;

class UserController extends Controller
{
    public function actionProfile()
    {
        $user = Yii::$app->user->identity;
        $books = UserBook::find()->where(['user_id' => $user->id])->all();
        $balance = Balance::findOne(['user_id' => $user->id]);
        return $this->render('profile', [
            'user' => $user,
            'balance' => $balance,
            'books' => $books,
        ]);
    }

    public function actionRequestWithdrawal()
    {
        $user = Yii::$app->user->identity;
        $balance = Balance::findOne(['user_id' => $user->id]);

        if ($balance === null || $balance->commission_amount <= 10) {
            Yii::$app->session->setFlash('error', 'You need at least $10 in your commission balance to request a withdrawal.');
            return $this->redirect(['profile']);
        }

        $withdrawal = new Withdrawal();
        $withdrawal->user_id = $user->id;
        $withdrawal->amount = $balance->commission_amount;
        if ($withdrawal->save()) {
            Yii::$app->session->setFlash('success', 'Your withdrawal request has been submitted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Unable to submit withdrawal request. Please try again.');
        }

        return $this->redirect(['profile']);
    }


}