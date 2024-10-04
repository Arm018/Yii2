<?php

namespace backend\controllers;

use common\models\User;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;

class UserController extends AdminController
{
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id): \yii\web\Response
    {
        $user = User::findOne($id);
        $user->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }
}