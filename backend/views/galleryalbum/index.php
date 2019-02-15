<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Sections;
use backend\models\Gallery;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GalleryalbumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Gallery Albums');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-album-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Gallery Album'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [ 'attribute' => 'photo', 'format' => 'raw', 'value' => function($data){
                $count = Gallery::find()->asArray()->where(['ownerId' =>$data->id])->count();
                return '<span class="glyphicon glyphicon-picture"></span> '.$count;
            }],

            //'id',
            //[ 'attribute' => 'id', 'format' => 'raw', 'value' => function($data){return '<span class="glyphicon glyphicon-flag"></span> '.$data->id;}],

            //'title',glyphicon glyphicon-picture
            [ 'attribute' => 'title', 'format' => 'raw', 'value' => function($data){return '<a href="/admin/galleryalbum/view?id='.$data->id.'"><span class="glyphicon glyphicon-cloud-upload"></span> '.$data->title.'</a>';}],

            //'id_section',
            [ 'attribute' => 'id_section', 'format' => 'raw', 'value' => function($data){
                $sections = Sections::find()->asArray()->where(['id' =>$data->id_section])->one();
                return '<span class="glyphicon glyphicon-tag"></span> '.$sections['title'];}],
            [ 'attribute' => 'active', 'format' => 'raw', 'value' => function($data){return $data->active? '<center><span style="color:#00ab30" class="glyphicon glyphicon-ok"></span></center>' : '<center><span style="color:#ab2610" class="glyphicon glyphicon-remove"></span></center>';}],
            [ 'attribute' => 'created', 'format' => 'raw', 'value' => function($data){return '<span class="glyphicon glyphicon-time"></span> '.date('d/m/Y', $data->created);}],
            // 'updated',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
