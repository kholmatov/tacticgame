<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use infoweb\gallery\GalleryAsset;

GalleryAsset::register($this);

/* @var $this yii\web\View */
/* @var $model infoweb\gallery\models\Gallery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gallery-form">

    <?php // Flash messages ?>
    <?php echo $this->render('_flash_messages'); ?>

    <?php
    // Init the form
    $form = ActiveForm::begin([
        'id'                        => 'gallery-form',
        'options'                   => ['class' => 'tabbed-form'],
        'enableAjaxValidation'      => true,
        'enableClientValidation'    => false
    ]);

    // Initialize the tabs
    $tabs = [];

    // Add the main tabs
    $tabs = [
        [
            'label' => Yii::t('infoweb/cms', 'General'),
            'content' => $this->render('_default_tab', ['model' => $model, 'form' => $form]),
            'active' => true,
        ],
        [
            'label' => Yii::t('infoweb/cms', 'Data'),
            'content' => $this->render('_data_tab', ['model' => $model, 'form' => $form]),
        ],
    ];

    // Display the tabs
    echo Tabs::widget(['items' => $tabs]);
    ?>

    <div class="form-group buttons">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create & close') : Yii::t('app', 'Update & close'), ['class' => 'btn btn-default', 'name' => 'close']) ?>
        <?= Html::submitButton(Yii::t('app', $model->isNewRecord ? 'Create & new' : 'Update & new'), ['class' => 'btn btn-default', 'name' => 'new']) ?>
        <?= Html::a(Yii::t('app', 'Close'), 'index', ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
