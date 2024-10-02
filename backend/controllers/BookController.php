<?php

namespace backend\controllers;

use backend\models\UploadForm;
use common\models\Author;
use common\models\Book;
use backend\models\BookSearch;
use backend\controllers\AdminController;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends AdminController
{

    /**
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Book model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Book();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $uploadPath = 'uploads/';
                $filePath = $uploadPath . $model->file->baseName . '.' . $model->file->extension;

                if ($model->file->saveAs($filePath)) {
                    $this->processXlsx($filePath);
                    Yii::$app->session->setFlash('success', 'File uploaded and books updated successfully.');
                } else {
                    Yii::$app->session->setFlash('error', 'There was an error saving the file.');
                }
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'File validation failed.');
            }
        }

        return $this->render('upload', ['model' => $model]);
    }

    protected function processXlsx($filePath)
    {
        $reader = new Xlsx();
        $spreadsheet = $reader->load($filePath);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $errorMessages = [];

        foreach ($sheetData as $row) {
            if ($row[0] == 'Title') {
                continue;
            }

            $book = Book::findOne(['title' => $row[0]]);
            if ($book === null) {
                $book = new Book();
            }
            $book->title = $row[0];
            $book->description = $row[1];
            $book->publication_year = $row[2];
            $authors = explode('|', $row[3]);
            $book->price = $row[4];
            $authorIds = [];
            $allAuthorsFound = true;

            foreach ($authors as $authorFullName) {
                $authorFullName = trim($authorFullName);
                $authorNameParts = preg_split('/\s+/', $authorFullName);

                if (count($authorNameParts) >= 2) {
                    $firstName = array_shift($authorNameParts);
                    $lastName = implode(' ', $authorNameParts);

                    $author = Author::findOne([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                    ]);

                    if ($author) {
                        $authorIds[] = $author->id;
                    } else {
                        $errorMessages[] = "No author found with name: $authorFullName";
                        $allAuthorsFound = false;
                    }
                } else {
                    $errorMessages[] = "No Author Found With Name: $authorFullName";
                    $allAuthorsFound = false;
                }
            }

            if ($allAuthorsFound) {
                $book->authorIds = $authorIds;
                if (!$book->save()) {
                    Yii::$app->session->setFlash('error', 'Error saving book: ' . $book->title);
                }
            } else {
                Yii::$app->session->setFlash('error', implode('<br>', $errorMessages));
            }
        }
        if (!empty($errorMessages)) {
            Yii::$app->session->setFlash('error', implode('<br>', $errorMessages));
        }
    }

}
