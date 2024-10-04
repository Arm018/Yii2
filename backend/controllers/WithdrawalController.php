<?php

namespace backend\controllers;

use common\models\User;
use common\models\Withdrawal;
use Yii;
use yii\db\Exception;
use yii\web\NotFoundHttpException;

class WithdrawalController extends AdminController
{

    public function actionIndex(): string
    {
        $withdrawals = Withdrawal::find()->all();
        return $this->render('index', [
            'withdrawals' => $withdrawals,
        ]);
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionApprove($id): \yii\web\Response
    {
        $withdrawal = Withdrawal::findOne($id);

        $user = User::findOne($withdrawal->user_id);
        $userBalance = $user->balance;

        $withdrawal->status = Withdrawal::STATUS_SUCCESS;

        if ($withdrawal->save()) {
            $userBalance->commission_amount -= $withdrawal->amount;
            if ($userBalance->save()) {
                Yii::$app->session->setFlash('success', 'Withdrawal request approved successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update user balance.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Failed to approve withdrawal request.');
        }

        return $this->redirect(['index']);
    }

    /**
     * @throws Exception
     */
    public function actionDecline($id): \yii\web\Response
    {
        $withdrawal = Withdrawal::findOne($id);

        $withdrawal->status = Withdrawal::STATUS_DECLINED;

        if ($withdrawal->save()) {
            Yii::$app->session->setFlash('success', 'Withdrawal request declined successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to decline withdrawal request.');
        }

        return $this->redirect(['index']);
    }

}