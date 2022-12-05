<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ocupacao */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="ocupacao-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ocupacao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_aviao')->textInput() ?>

    <?= $form->field($model, 'id_classe')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>