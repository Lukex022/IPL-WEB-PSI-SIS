<?php

namespace backend\controllers;

use Yii;
use common\models\Tarefa;
use common\models\Voo;
use app\models\TarefaSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use \PhpMqtt\Client\MqttClient;

/**
 * TarefaController implements the CRUD actions for Tarefa model.
 */
class TarefaController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' =>[
                    [
                        'allow' => true,
                        'actions'=> ['index'],
                        'roles' => ['indexTarefa'],
                    ],
                    [
                        'allow' => true,
                        'actions'=> ['create'],
                        'roles' => ['createTarefa'],
                    ],
                    [
                        'allow' => true,
                        'actions'=> ['view'],
                        'roles' => ['viewTarefa'],
                    ],
                    [
                        'allow' => true,
                        'actions'=> ['update'],
                        'roles' => ['updateTarefa'],
                    ],
                    [
                        'allow' => true,
                        'actions'=> ['delete'],
                        'roles' => ['deleteTarefa'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Tarefa models.
     * @return mixed
     */
    public function actionIndex($vooid)
    {
        $voo = Voo::findOne($vooid);
        $searchModel = new TarefaSearch();
        $dataProvider = new ActiveDataProvider(['query' => $voo->getTarefas(),]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'voo' => $voo,
        ]);
    }

    /**
     * Displays a single Tarefa model.
     * @param string $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tarefa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($vooid)
    {
        //vooid  and id_funcionario_registo
        $voo = Voo::findOne($vooid);
        $model = new Tarefa();
        $model->id_voo = $vooid;
        $funcionario = Yii::$app->db->createCommand("Select * from utilizador where id_user='".Yii::$app->user->id."'")->queryOne();
        $model->id_funcionario_registo = $funcionario['id'];

        if ($model->load(Yii::$app->request->post())) {

            if ($model->id_recurso != '' && $model->quantidade == ''){
                return $this->render('create', [
                    'model' => $model,
                    'message'=>'Tem que inserir a quantidade do recurso!',
                    'voo' => $voo,
                ]);
            }elseif ($model->id_recurso == '' && $model->quantidade != ''){
                return $this->render('create', [
                    'model' => $model,
                    'message'=>'Tem que inserir o recurso para puder ter uma quantidade!',
                    'voo' => $voo,
                ]);
            }else{

                $server = '127.0.0.1';
                $port = 1883;


                $mqtt = new \PhpMqtt\Client\MqttClient($server,$port);

                $mqtt->connect();
                $mqtt->publish('tarefas','Tarefa criada: '.$model->designacao,1);
                $mqtt->disconnect();


                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'message'=>null,
            'voo' => $voo,
        ]);
    }

    /**
     * Updates an existing Tarefa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $voo = Voo::findOne($model->id_voo);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $server = '127.0.0.1';
            $port = 1883;


            $mqtt = new \PhpMqtt\Client\MqttClient($server,$port);

            $mqtt->connect();
            $mqtt->publish('tarefas','Tarefa editada: '.$model->designacao,1);
            $mqtt->disconnect();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'voo' =>$voo,
        ]);
    }

    /**
     * Deletes an existing Tarefa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tarefa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id ID
     * @return Tarefa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tarefa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
