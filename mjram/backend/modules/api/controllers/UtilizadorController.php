<?php

namespace backend\modules\api\controllers;

use \yii\rest\ActiveController;
use Yii;
use common\models\Utilizador;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class UtilizadorController extends ActiveController
/**
 * UtilizadorController implements the CRUD actions for Utilizador model.
 */
{
    public $modelClass = 'common\models\User'; //Parte CRUD
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Utilizador models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UtilizadorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


    /**
     * Displays a single Utilizador model.
     * @param string $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    /**
     * Creates a new Utilizador model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Utilizador();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Utilizador model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id ID
     * @return mixed
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

    public function actionList()
    {
        $utilizadorModel = new $this->modelClass;
        return $utilizadorModel::find()->all();
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Utilizador model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id ID
     * @return Utilizador the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Utilizador::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
