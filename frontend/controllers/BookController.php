<?php

namespace frontend\controllers;

use common\models\Balance;
use common\models\Book;
use common\models\User;
use common\models\UserBook;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BookController extends Controller
{

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Book::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $book = Book::findOne($id);
        return $this->render('view', [
            'book' => $book,
        ]);
    }

    public function actionBuy($id)
    {
        $book = Book::findOne($id);
        if (!$book) {
            throw new NotFoundHttpException('Book not found');
        }

        $user = Yii::$app->user->identity;
        if ($user === null) {
            return $this->redirect(['site/login']);
        }

        $existingPurchase = UserBook::find()
            ->where(['user_id' => $user->getId(), 'book_id' => $book->id])
            ->one();

        if ($existingPurchase) {
            Yii::$app->session->setFlash('error', 'You have already purchased this book.');
            return $this->redirect(['book/index']);
        }

        $book_price = $book->price;
        $balance = Balance::findOne(['user_id' => $user->getId()]);
        if ($balance === null || $balance->amount < $book_price) {
            Yii::$app->session->setFlash('error', 'You don\'t have enough balance to buy this book');
            return $this->redirect(['book/index']);
        }

        $balance->amount -= $book_price;
        if (!$balance->save()) {
            Yii::$app->session->setFlash('error', 'Unable to buy this book, Please try again');
            return $this->redirect(['book/index']);
        }

        $commission = 0;
        if ($user->referrer_id) {
            $referrer = User::findOne($user->referrer_id);
            if ($referrer) {
                $commission = $book_price * 0.1;
                $referrer_balance = Balance::findOne(['user_id' => $referrer->getId()]);
                if ($referrer_balance) {
                    $referrer_balance->commission_amount += $commission;
                    $referrer_balance->save();

                }
            }
        }

        $userBook = new UserBook();
        $userBook->user_id = $user->getId();
        $userBook->book_id = $book->id;
        $userBook->amount = $book_price;
        $userBook->commission = $commission;
        if (!$userBook->save()) {
            Yii::$app->session->setFlash('error', 'Unable to record the purchase, Please try again');
            return $this->redirect(['book/index']);
        }

        Yii::$app->session->setFlash('success', 'You have successfully purchased this book');
        return $this->redirect(['book/index']);
    }



}