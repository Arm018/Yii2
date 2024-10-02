<?php

namespace backend\controllers;

use backend\controllers\AdminController;
use common\models\Balance;
use common\models\Withdrawal;
use Yii;
use yii\db\Exception;
use yii\web\NotFoundHttpException;

class WithdrawalController extends AdminController
{

    public function actionIndex()
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
        if (!$withdrawal) {
            throw new NotFoundHttpException('Withdrawal request not found.');
        }
        $withdrawal->status = 'approved';
        $withdrawal->request_date = date('Y-m-d H:i:s');

        if ($withdrawal->save()) {
            $userBalance = Balance::findOne(['user_id' => $withdrawal->user_id]);
            if ($userBalance) {
                $userBalance->commission_amount -= $withdrawal->amount;
                $userBalance->save();
            }

            Yii::$app->session->setFlash('success', 'Withdrawal request approved successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to approve withdrawal request.');
        }

        return $this->redirect(['index']);
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionDecline($id): \yii\web\Response
    {
        $withdrawal = Withdrawal::findOne($id);
        if (!$withdrawal) {
            throw new NotFoundHttpException('Withdrawal request not found.');
        }

        $withdrawal->status = 'declined';

        if ($withdrawal->save()) {
            Yii::$app->session->setFlash('success', 'Withdrawal request declined successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to decline withdrawal request.');
        }

        return $this->redirect(['index']);
    }

}