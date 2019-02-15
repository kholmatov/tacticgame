<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//Maps
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\layers\BicyclingLayer;
use dosamigos\google\maps\services\GeocodingClient;

/* @var $this yii\web\View */
/* @var $model app\models\Position */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="position-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'title_es')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'address_es')->textInput(['maxlength' => true]) ?>
<!--
    <div class="form-group field-position-sections">
        <label for="position-sections" class="control-label"><?php //Yii::t('app','Sections')?></label>
        <?php
            //$sectItem = ArrayHelper::map($sectItem,'id','title');
            //$sectPostItem = ArrayHelper::getColumn($sectPostItem, 'section_id');
            //echo  Html::checkboxList('sections',$sectPostItem,$sectItem,['class'=>'checkboxlist']);
        ?>
    </div>
    -->

    <?= $form->field($model, 'active')->checkbox() ?>
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

            $map->height='200';
            $map->width='100%';

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

    <div class="form-group" style="margin-top: 15px;">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
