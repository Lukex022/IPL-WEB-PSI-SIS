<?php

namespace backend\modules\api\controllers;

use \yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User'; //Parte CRUD

}