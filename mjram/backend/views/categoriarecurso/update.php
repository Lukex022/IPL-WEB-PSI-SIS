<?php

/* @var $this yii\web\View */
/* @var $model common\models\CategoriaRecurso */

$this->title = 'Update Categoria Recurso: ' . $model->designacao;
$this->params['breadcrumbs'][] = ['label' => 'Categoria Recursos', 'url' => ['index']];
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