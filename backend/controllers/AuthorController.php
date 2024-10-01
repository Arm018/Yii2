<?php

namespace backend\controllers;

use backend\models\UploadForm;
use common\models\Author;
use backend\models\AuthorSearch;
use backend\controllers\AdminController;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * AuthorController implements the CRUD actions for Author model.
 */
class AuthorController extends AdminController
{
    /**
     * Lists all Author models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AuthorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Author model.
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
     * Creates a new Author model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Author();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Author model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Author model.
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
     * Finds the Author model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Author the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Author::findOne(['id' => $id])) !== null) {
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

                $filePath = $model->file->baseName . '.' . $model->file->extension;
                if ($model->file->saveAs($filePath)) {
                    $this->processXlsx($filePath);
                    Yii::$app->session->setFlash('success', 'File uploaded and authors updated successfully.');
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
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        foreach ($sheetData as $row) {
            if ($row['A'] === 'First Name' && $row['B'] === 'Last Name' && $row['C'] === 'Biography') {
                continue;
            }

            $author = new Author();
            $author->first_name = $row['A'];
            $author->last_name = $row['B'];
            $author->biography = $row['C'];

            if (!$author->save()) {
                Yii::error('Error saving author: ' . implode(', ', $author->getFirstErrors()), __METHOD__);
            }
        }
    }
}
