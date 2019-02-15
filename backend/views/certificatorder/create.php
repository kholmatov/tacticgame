<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Certificatorder */

$this->title = Yii::t('app', 'Create Certificatorder');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Certificatorders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="certificatorder-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
