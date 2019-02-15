<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GameItems */

$this->title = Yii::t('app', 'Create Game Items');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Game Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-items-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
