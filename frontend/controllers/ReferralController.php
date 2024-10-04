<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

class ReferralController extends Controller
{
    public function actionIndex(): string
    {
        $user = Yii::$app->user->identity;
        $referralData = $this->getMonthlyReferralCommissions($user->getId());

        return $this->render('index', [
            'user' => $user,
            'referralData' => $referralData
        ]);
    }

    private function getMonthlyReferralCommissions($userId): array
    {
        $monthlyData = array_fill(0, 12, 0.0);

        $results = (new \yii\db\Query())
            ->select(['MONTH(purchase_date) AS month', 'SUM(commission) AS total_commission'])
            ->from('{{%user_book}} ub')
            ->innerJoin('{{%user}} u', 'ub.user_id = u.id')
            ->where(['YEAR(purchase_date)' => date('Y')])
            ->andWhere(['u.referrer_id' => $userId])
            ->groupBy('month')
            ->all();

        foreach ($results as $result) {
            $monthlyData[$result['month'] - 1] = (float)$result['total_commission'];
        }

        return $monthlyData;
    }


}
