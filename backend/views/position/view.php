<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
//Maps
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\layers\BicyclingLayer;
use dosamigos\google\maps\services\GeocodingClient;
/* @var $this yii\web\View */
/* @var $model app\models\Position */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Positions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="position-view">

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
    $TitleContent="";
//   if(is_array($getSections)){
//        foreach($getSections as $sitem){
//            $TitleContent.=$sitem['title']." | ";
//        }
//
//   }
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'title_es',
            'address',
            'address_es',
            'latitude',
            'longitude',
            [
             'label' => Yii::t('app','Active'),
             'value' => $model->active ? Yii::t('app','Yes') : Yii::t('app','No')
            ]
        ],
    ]) ?>

</div>
<?php
if($model->address){

    $gc = new GeocodingClient();
    $result = $gc->lookup(array('address'=>$model->address,'components'=>'country'));
    $location = $result['results'][0]['geometry']['location'];
    if (!is_null($location)) {
        $lat = $location['lat'];
        $lng = $location['lng'];
        $coord = new LatLng(['lat' => $lat, 'lng' => $lng]);
        $map = new Map([
            'center' => $coord,
            'zoom' => 16
        ]);

        $map->height = '200';
        $map->width = '100%';

        // Lets add a marker now
        $marker = new Marker([
            'position' => $coord,
            'title' => 'Our Progect',
            'icon'=>'/../tacticgamepoint.png',

        ]);

        // Add marker to the map
        $map->addOverlay($marker);
        //========================================================
        // Lets show the BicyclingLayer :)
        $bikeLayer = new BicyclingLayer(['map' => $map->getName()]);

        // Append its resulting script
        $map->appendScript($bikeLayer->getJs());

        // Display the map -finally :)
        echo $map->display();
    }
}
?>
