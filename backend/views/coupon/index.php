<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Sections;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CouponSeacrh */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Game Coupons');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-coupon-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Coupon'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
    $sections = Sections::find()->asArray()->all();
    $sArray = [];
    foreach($sections as $row){
        $sArray[$row['id']] = $row['title'];
    }

    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function($data){
                    return '<a href="/admin/coupon/view?id='.$data->id.'">'.$data->title.'</a> <span class="label label-primary">'.$data->number.'</span>';
            }
            ],
            [
                'attribute' => 'Sections',
                'format' => 'raw',
                'value' => function($data) use($sArray){
                    $sect = explode(",", $data->section_id);
                    $shtml = " ";
                    foreach($sect as $row){
                        $shtml .= '<span class="label label-default">'.$sArray[$row].'</span><br>';
                    }

                    return $shtml;
                }

            ],
            'start',
            'end',
            [ 'attribute' => 'status', 'format' => 'raw', 'value' => function($data){return $data->status? '<center><span style="color:#00ab30" class="glyphicon glyphicon-ok"></span></center>' : '<center><span style="color:#ab2610" class="glyphicon glyphicon-remove"></span></center>';}],

            // 'discount',
            // 'number',
            // 'count',
            // 'status',
            // 'createdate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
