<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Certification;
use backend\models\Order;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CertificatorderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Certificat Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="certificatorder-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    $orders = Order::find()->where('certificate <> :certificate',[':certificate'=>''])->asArray()->all();
    $oArray = [];
     foreach($orders as $row){
         $oArray[$row['certificate']] = $row['certificate'];
     }

    $sections = Certification::find()->asArray()->all();
    $sArray = [];
    foreach($sections as $row){
        $sArray[$row['id']] = $row['title'];
    }

    ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Certificat Order'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'order',
            'createdate',
            ['attribute'=> 'period', 'format' => 'raw', 'value' => function($data){
                return date('Y-m-d', strtotime("+".$data->period." months", strtotime($data->createdate)));
            }],
            //'code',
            [
                'attribute' => 'code',
                'format' => 'raw',
                'value' => function($data) use($oArray){

                    if(isset($oArray[$data['code']])&&$oArray[$data['code']]==$data['code']){
                        $shtml = '<span class="label label-danger">'.$data['code'].'</span><br>';
                    }else{
                        $shtml = '<span class="label label-success">'.$data['code'].'</span><br>';
                    }
                    return $shtml;
                }

            ],
            'users',
//            [
//                'attribute' => 'Sections',
//                'format' => 'raw',
//                'value' => function($data) use($sArray){
//                    $sect = explode(",", $data->sect_id);
//                    $shtml = " ";
//                    foreach($sect as $row){
//                        $shtml .= '<span class="label label-default">'.$sArray[$row].'</span><br>';
//                    }
//
//                    return $shtml;
//                }
//
//            ],
            'price',
            'email:email',
            'phone',
            // 'comment:ntext',

            ['attribute' => 'status', 'format' => 'raw', 'value' => function($data){return $data->status? '<center><span style="color:#00ab30" class="glyphicon glyphicon-ok"></span></center>' : '<center><span style="color:#ab2610" class="glyphicon glyphicon-remove"></span></center>';}],


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
