<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\GraficOrder */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Grafic Order',
]) . ' ' . $model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Grafic Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sections' => $sections
    ]) ?>

</div>
