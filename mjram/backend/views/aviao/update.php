<?php

/* @var $this yii\web\View */
/* @var $model common\models\Aviao */

$this->title = 'Update Aviao: ' . $model->designacao;
$this->params['breadcrumbs'][] = ['label' => 'Aviaos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?=$this->render('_form', [
                        'model' => $model
                    ]) ?>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>