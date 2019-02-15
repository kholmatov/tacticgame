<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\GraficOrder */

$this->title = Yii::t('app', 'Create Grafic Order');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Grafic Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sections' => $sections,
    ]) ?>

</div>
