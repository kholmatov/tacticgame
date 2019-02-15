<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CertificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Certifications');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="certification-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Certification'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            //'title_es',
            //'text:ntext',
            //'text_es:ntext',
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
            'period',
            [ 'attribute' => 'photo', 'format' => 'raw', 'value' => function($data){
                return '<img height="100" src="/../upload'.$data->photo.'">';
            }],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
