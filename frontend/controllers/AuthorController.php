<?php

namespace frontend\controllers;

use common\models\Author;
use yii\web\Controller;

class AuthorController extends Controller
{
    public function actionIndex()
    {
        $authors = Author::find()->all();
        return $this->render('index', [
            'authors' => $authors,
        ]);
    }

    public function actionView($id)
    {
        $author = Author::findOne($id);
        return $this->render('view', [
            'author' => $author,
        ]);
    }

}