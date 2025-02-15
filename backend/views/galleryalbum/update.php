<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Galleryalbum */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Gallery Album',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gallery Albums'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="gallery-album-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sections'=>$sections
    ]) ?>

</div>
