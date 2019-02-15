<?php

use yii\helpers\Html;
use backend\models\Gallerycounter;

/* @var $this yii\web\View */
/* @var $model app\models\Galleryalbum */

$this->title = Yii::t('app', 'Create Gallery Album');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gallery Albums'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-album-create">

    <h1><?= Html::encode($this->title) ?></h1>
        <?php
        $counterResult = Gallerycounter::find()->asArray()->where(['id' =>1])->one();
        $model->title = $counterResult['counter'];
        ?>
    <?= $this->render('_form', [
        'model' => $model,
        'sections'=>$sections
    ]) ?>

</div>
