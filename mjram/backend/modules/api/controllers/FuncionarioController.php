<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\User;
use common\models\Utilizador;
use common\models\Funcionario;
use Yii;

class FuncionarioController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\Funcionario';

    public function behaviors()
    {
        Yii::$app->params['id'] = 0;
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::className(),
            'except'=>[],
        ];
        return $behaviors;
    }


    public function checkAccess($action, $model = null, $params = [])
    {
        $role = Yii::$app->db->createCommand("Select * from auth_assignment where user_id ='".Yii::$app->params['id']."'")->queryOne();
        if($role['item_name'] != 'funcionarioManutencao')
        {
            throw new \yii\web\ForbiddenHttpException('Proibido por nao ser um Funcionario Manutencao!');
        }

        if($role['item_name'] == 'funcionarioManutencao')
        {
            if($action==="create" || $action==="update" || $action==="delete" || $action==="index"|| $action==="view")
            {
                throw new \yii\web\ForbiddenHttpException('Proibido! Nao tem acesso a esta funçao!');
            }
        }
    }

    public function actionGetutilizador($id){
        $utilizador = Yii::$app->db->createCommand("Select * from utilizador where id_user ='".Yii::$app->params['id']."'")->queryOne();
        if($id === $utilizador['id']){
            $model = Utilizador::findOne($id);
            return $model;
        }else{
            throw new \yii\web\ForbiddenHttpException('Proibido! Pois estás a tentar aceder a dados que nao sao teus!');
        }
    }

    public function actionGetuser($id){
        $utilizador = Yii::$app->db->createCommand("Select * from utilizador where id_user ='".Yii::$app->params['id']."'")->queryOne();
        if($id === $utilizador['id']){
            return $this->actionGetutilizador($id)->user;
        }else{
            throw new \yii\web\ForbiddenHttpException('Proibido! Pois estás a tentar aceder a dados que nao sao teus!');
        }
    }

    public function actionGetrole($id){
        $utilizador = Yii::$app->db->createCommand("Select * from utilizador where id_user ='".Yii::$app->params['id']."'")->queryOne();
        if($id === $utilizador['id']){
            $modelUtilizador = $this->actionGetutilizador($id);
            $userRole = Yii::$app->db ->createCommand("Select * from auth_assignment where user_id='".$modelUtilizador->id_user."'")->queryOne();
            return $userRole;
        }else{
            throw new \yii\web\ForbiddenHttpException('Proibido! Pois estás a tentar aceder a dados que nao sao teus!');
        }

    }

    public function actionGetnib($id){
        $utilizador = Yii::$app->db->createCommand("Select * from utilizador where id_user ='".Yii::$app->params['id']."'")->queryOne();
        if($id === $utilizador['id']){
            $modelUtilizador = $this->actionGetutilizador($id);
            return $modelUtilizador->funcionario->nib;
        }else{
            throw new \yii\web\ForbiddenHttpException('Proibido! Pois estás a tentar aceder a dados que nao sao teus!');
        }

    }



    public function actionPerfil($username){
        $user = User::findByUsername($username);
        if(isset($user)){
            if($user->id == Yii::$app->params['id']){


                $modelUtilizador = Utilizador::findOne(['id_user'=>$user->id]);
                $modelFuncionario = Funcionario::findOne(['id'=> $modelUtilizador->id]);

                return [
                    'email'=>$user->email,
                    'nib'=>$modelFuncionario->nib,
                    'telemovel'=>$modelUtilizador->telemovel,
                    'nomes'=>$modelUtilizador->nome." ".$modelUtilizador->apelidos,
                    'dataregisto'=>$modelUtilizador->data_registo,
                ];


            }else{
                throw new \yii\web\ForbiddenHttpException('Proibido! Pois estás a tentar aceder a dados que nao sao teus!');
            }
        }else{

            throw new \yii\web\UnprocessableEntityHttpException('O username especificado não existe');
        }

    }
}