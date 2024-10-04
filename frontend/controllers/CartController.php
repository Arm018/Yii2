<?php

namespace frontend\controllers;

use common\models\Balance;
use common\models\Book;
use common\models\Cart;
use common\models\User;
use common\models\UserBook;
use common\services\CommissionService;
use Yii;
use yii\db\Exception;
use yii\web\Controller;

class CartController extends Controller
{
    private CommissionService $commissionService;

    public function __construct($id, $module, CommissionService $commissionService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->commissionService = $commissionService;
    }

    public function actionIndex(): string
    {
        $user = Yii::$app->user->identity;
        $cartItems = $user->getCart()->with('book')->all();

        return $this->render('index', [
            'cartItems' => $cartItems,
        ]);
    }

    /**
     * @throws Exception
     */
    public function actionAdd($id): \yii\web\Response
    {
        $user = Yii::$app->user->identity;

        if ($user === null) {
            return $this->redirect(['site/login']);
        }

        $quantity = Yii::$app->request->post('quantity')[$id];
        $existingCart = $this->cartExists($id);

        if ($existingCart) {
            $existingCart->quantity += $quantity;
            $existingCart->save();
        } else {
            $this->createCart($id, $quantity);
        }

        Yii::$app->session->setFlash('success', 'Book added to your cart.');
        return $this->redirect(['book/index']);
    }

    public function actionRemove($id): \yii\web\Response
    {
        $user = Yii::$app->user->identity;

        if ($user === null) {
            return $this->redirect(['site/login']);
        }

        $cartItem = $user->getCart()->where(['id' => $id])->one();

        if ($cartItem !== null) {
            $cartItem->delete();
            Yii::$app->session->setFlash('success', 'Book removed from your cart.');
        } else {
            Yii::$app->session->setFlash('error', 'Item not found in your cart.');
        }

        return $this->redirect(['cart/index']);
    }

    /**
     * @throws Exception
     */
    public function actionBuy(): \yii\web\Response
    {
        $user = Yii::$app->user->identity;
        if ($user === null) {
            return $this->redirect(['site/login']);
        }

        $post = Yii::$app->request->post();
        $bookIds = array_keys($post['quantity']);

        foreach ($bookIds as $bookId) {
            $this->processBookPurchase($user, $bookId, $post['quantity'][$bookId]);
        }

        return $this->redirect(['user/profile']);
    }

    /**
     * @throws Exception
     */
    private function processBookPurchase($user, $bookId, $quantity)
    {
        $book = Book::findOne($bookId);
        if (!$book) {
            Yii::$app->session->setFlash('error', 'Book not found.');
            return;
        }

        $bookPrice = $this->calculateBookPrice($book, $quantity);
        $balance = $user->getBalance()->one();

        if (!$this->hasEnoughBalance($balance, $bookPrice)) {
            Yii::$app->session->setFlash('error', 'You don\'t have enough balance to buy this book');
            return;
        }

        if (!$this->deductBalance($balance, $bookPrice)) {
            Yii::$app->session->setFlash('error', 'Unable to buy this book, Please try again');
            return;
        }

        $commission = $this->commissionService->handleCommission($user, $book, $quantity);
        if (!$this->recordUserBook($user->getId(), $book->id, $bookPrice, $quantity, $commission)) {
            Yii::$app->session->setFlash('error', 'Unable to record the purchase, Please try again');
            return;
        }

        $this->removeFromCart($user->id, $book->id);
        Yii::$app->session->setFlash('success', 'You have successfully purchased the book(s)');
    }

    private function calculateBookPrice($book, $quantity)
    {
        return $book->price * (int)$quantity;
    }

    private function hasEnoughBalance($balance, $bookPrice): bool
    {
        return $balance && $balance->amount >= $bookPrice;
    }

    private function deductBalance($balance, $bookPrice)
    {
        if ($balance) {
            $balance->amount -= $bookPrice;
            return $balance->save();
        }
        return false;
    }

    /**
     * @throws Exception
     */
    private function recordUserBook($userId, $bookId, $amount, $quantity, $commission): bool
    {
        $userBook = new UserBook();
        $userBook->user_id = $userId;
        $userBook->book_id = $bookId;
        $userBook->amount = $amount;
        $userBook->quantity = (int)$quantity;
        $userBook->commission = $commission;

        return $userBook->save();
    }

    private function removeFromCart($userId, $bookId)
    {
        $user = Yii::$app->user->identity;
        $cartItem = $user->getCart()->andWhere(['user_id' => $userId, 'book_id' => $bookId])->one();
        if ($cartItem) {
            $cartItem->delete();
        }
    }

    /**
     * @throws Exception
     */
    private function createCart($id, $quantity)
    {
        $user = Yii::$app->user->identity;
        $cart = new Cart();
        $cart->user_id = $user->id;
        $cart->book_id = $id;
        $cart->quantity = $quantity;
        $cart->save();
    }

    private function cartExists($bookId)
    {
        $user = Yii::$app->user->identity;
        return $user->getCart()->andWhere(['book_id' => $bookId])->one();
    }

}
