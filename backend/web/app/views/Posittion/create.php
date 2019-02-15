<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Posittion */

$this->title = Yii::t('app', 'Create Posittion');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posittions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posittion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
