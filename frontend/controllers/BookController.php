<?php

namespace frontend\controllers;

use common\models\Balance;
use common\models\Book;
use common\models\Order;
use common\models\OrderItem;
use common\models\User;
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

        return $this->recordOrder(Yii::$app->user->identity->getId(), $book->id, $book->price, $commission);
    }


    /**
     * @throws Exception
     */
    private function recordOrder($userId, $bookId, $amount, $commission): bool
    {
        $transaction = Yii::$app->db->beginTransaction();

        $order = new Order();
        $order->user_id = $userId;
        $order->total_amount = $amount;
        $order->commission = $commission;

        if (!$order->save()) {
            $transaction->rollBack();
            return false;
        }

        $orderItem = new OrderItem();
        $orderItem->order_id = $order->id;
        $orderItem->book_id = $bookId;
        $orderItem->quantity = 1;
        $orderItem->amount = $amount;
        $orderItem->commission = $commission;

        if (!$orderItem->save()) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;

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