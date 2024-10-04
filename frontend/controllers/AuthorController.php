<?php

namespace frontend\controllers;

use common\models\Author;
use yii\web\Controller;

class AuthorController extends Controller
{
    public function actionIndex(): string
    {
        $authors = Author::find()->all();
        return $this->render('index', [
            'authors' => $authors,
        ]);
    }

    public function actionView($id): string
    {
        $author = Author::findOne($id);
        return $this->render('view', [
            'author' => $author,
        ]);
    }

}