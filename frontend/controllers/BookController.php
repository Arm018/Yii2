<?php

namespace frontend\controllers;

use common\models\Balance;
use common\models\Book;
use common\models\User;
use common\models\UserBook;
use common\services\CommissionService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BookController extends Controller
{
    private CommissionService $commissionService;

    public function __construct($id, $module, CommissionService $commissionService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->commissionService = $commissionService;
    }

    public function actionIndex(): string
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

    public function actionView($id): string
    {
        $book = Book::findOne($id);
        return $this->render('view', [
            'book' => $book,
        ]);
    }

    public function actionBuy($id): \yii\web\Response
    {
        $book = Book::findOne($id);

        $user = Yii::$app->user->identity;
        if ($user === null) {
            return $this->redirect(['site/login']);
        }

        $balance = $this->getUserBalance($user);
        if (!$this->hasEnoughBalance($balance, $book->price)) {
            Yii::$app->session->setFlash('error', 'You don\'t have enough balance to buy this book');
            return $this->redirect(['book/index']);
        }

        if (!$this->processPurchase($balance, $book)) {
            Yii::$app->session->setFlash('error', 'Unable to buy this book, Please try again');
            return $this->redirect(['book/index']);
        }

        Yii::$app->session->setFlash('success', 'You have successfully purchased this book');
        return $this->redirect(['book/index']);
    }

    /**
     * @throws Exception
     */
    private function processPurchase($balance, $book): bool
    {
        $balance->amount -= $book->price;
        if (!$balance->save()) {
            return false;
        }
        $commission = $this->commissionService->handleCommission(Yii::$app->user->identity, $book, 1);

        return $this->recordUserBook(Yii::$app->user->identity->getId(), $book->id, $book->price, $commission);
    }


    /**
     * @throws Exception
     */
    private function recordUserBook($userId, $bookId, $amount, $commission): bool
    {
        $userBook = new UserBook();
        $userBook->user_id = $userId;
        $userBook->book_id = $bookId;
        $userBook->amount = $amount;
        $userBook->quantity = 1;
        $userBook->commission = $commission;
        return $userBook->save();
    }




    private function getUserBalance($user)
    {
        return $user->getBalance()->one();
    }

    private function hasEnoughBalance($balance, $bookPrice): bool
    {
        return $balance && $balance->amount >= $bookPrice;
    }


}