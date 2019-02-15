<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Sections;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\GraficOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Grafic Order'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'code',
            //'sections_id',
            [ 'attribute' => 'Sections', 'format' => 'raw', 'value' => function($data){
                $sections = Sections::find()->asArray()->where(['id' =>$data->sections_id])->one();
                return $sections['title'];}],
            'date',
            //[ 'attribute' => 'date', 'format' => 'raw', 'value' => function($data){return '<span class="glyphicon glyphicon-time"></span> '.date('d/m/Y', $data->date);}],
             'time',
             'phone',
             'email:email',
             'amount',
             //'comment:ntext',
             //'coupon_code',
             //'certificate',
            ['attribute' => 'comment', 'format' => 'raw', 'value' =>function($data){
                $certificate =""; if($data->certificate) $certificate ="<p><b>Certificate: ".$data->certificate."</b></p>";
                $coupon_code =""; if($data->coupon_code) $coupon_code ="<p><b>Promocode: ".$data->coupon_code."</b></p>";
                return $data->comment.$certificate.$coupon_code;
            }],
            ['attribute' => 'status', 'format' => 'raw', 'value' => function($data){return $data->status? '<center><span style="color:#00ab30" class="glyphicon glyphicon-ok"></span></center>' : '<center><span style="color:#ab2610" class="glyphicon glyphicon-remove"></span></center>';}],

            'payment_type',
            // 'check',
             'createdate',


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
