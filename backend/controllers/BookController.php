<?php

namespace backend\controllers;

use backend\models\Admin;
use Yii;
use common\models\Book;
use common\models\Author;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

class BookController extends AdminController
{

    public function actionIndex()
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => Book::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Book();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                Yii::$app->db->createCommand()->delete('books_authors', ['book_id' => $model->id])->execute();

                if (!empty($model->authorIds)) {
                    foreach ($model->authorIds as $authorId) {
                        Yii::$app->db->createCommand()->insert('books_authors', [
                            'book_id' => $model->id,
                            'author_id' => $authorId,
                        ])->execute();
                    }
                }

                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'authors' => Author::getAuthorList(),
        ]);
    }



    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $authorIds = Yii::$app->request->post('Book')['author_ids'];

                $this->saveBookAuthors($model->id, $authorIds);

                return $this->redirect(['index']);
            }
        }

        // Prepare data for the view
        return $this->render('update', [
            'model' => $model,
            'authors' => Author::getAuthorList(),
        ]);
    }

    /**
     * Save the many-to-many relationship between Book and Author
     *
     * @param int $bookId
     * @param array $authorIds
     */
    protected function saveBookAuthors($bookId, $authorIds)
    {
        Yii::$app->db->createCommand()
            ->delete('books_authors', ['book_id' => $bookId])
            ->execute();

        if (!empty($authorIds)) {
            $rows = [];
            foreach ($authorIds as $authorId) {
                $rows[] = [$bookId, $authorId];
            }

            Yii::$app->db->createCommand()
                ->batchInsert('books_authors', ['book_id', 'author_id'], $rows)
                ->execute();
        }
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Book::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
