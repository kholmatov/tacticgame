<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Sections;
use zxbodya\yii2\galleryManager\GalleryManager;


/* @var $this yii\web\View */
/* @var $model app\models\Galleryalbum */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gallery Albums'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-album-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php
    $sections = Sections::find()->asArray()->where(['id' =>$model->id_section])->all();

    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute'=>'Sections',
                'value'=>$sections[0]['title']
            ],
            //'active',
            [
                'label' => Yii::t('app','Active'),
                'value' => $model->active ? Yii::t('app','Yes') : Yii::t('app','No')
            ],
            // 'created',
            [
                'attribute' => 'created',
                'format' => ['date', 'php:d/m/Y']
            ],


        ],
    ])
    ?>
    <?php
    if ($model->isNewRecord) {
        echo 'Can not upload images for new record';
    } else {
        echo GalleryManager::widget(
            [
                'model' => $model,
                'behaviorName' => 'galleryBehavior',
                'apiRoute' => 'galleryalbum/galleryApi'
            ]
        );
    }
    ?>
</div>
