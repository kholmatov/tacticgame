<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CertificatSectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Certificat Sections');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="certificat-section-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a(Yii::t('app', 'Create Certificat Section'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'title',
            [ 'attribute' => 'start_date', 'format' => 'raw', 'value' => function($data){return '<span class="glyphicon glyphicon-time"></span> '.date('F j, Y', strtotime($data->start_date));}],
            [ 'attribute' => 'finish_date', 'format' => 'raw', 'value' => function($data){return '<span class="glyphicon glyphicon-time"></span> '.date('F j, Y', strtotime($data->finish_date));}],
            [ 'attribute' => 'gamers', 'format' => 'raw', 'value' => function($data){
                $gamers = explode('|', $data->gamers);
                if(count($gamers) > 1) {
                    $rs_html = '<ul class="col-md-8 items-wrap" style="list-style:none">';
                    foreach ($gamers as $rows) {
                        $gamer = explode('-', $rows);
                        $rs_html .= "<li class='tariff-li'>";
                        $rs_html .= "<i class='glyphicon glyphicon-user'></i> ".$gamer[0];
                        $rs_html .= " - <span class='tariff-color'>" . $gamer[1] . "€</li>";
                    }

                    $rs_html .= '</ul>';
                    return $rs_html;
                }
            }],
            [ 'attribute' => 'number', 'format' => 'raw', 'value' => function($data){return '<center>'.$data->number.' - <span style="color:#00ab30" class="glyphicon glyphicon-gift"></span></center>';}],
            // 'createdate',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
