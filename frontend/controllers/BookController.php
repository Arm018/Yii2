<?php

namespace frontend\controllers;

use common\models\Book;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BookController extends Controller
{

    public function actionIndex()
    {
        $books = Book::find()->all();

        return $this->render('index', [
            'books' => $books,
        ]);
    }

    public function actionView($id)
    {
        $book = Book::findOne($id);
        return $this->render('view', [
            'book' => $book,
        ]);
    }


}