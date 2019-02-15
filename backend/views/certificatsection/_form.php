<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CertificatSection */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="certificat-section-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_date')->widget(\yii\jui\DatePicker::classname(), [
        //'language' => 'ru',
        'value'  => $model->start_date,
        'dateFormat' => 'MM/dd/yyyy',
    ]) ?>

    <?= $form->field($model, 'finish_date')->widget(\yii\jui\DatePicker::classname(), [
        //'language' => 'ru',
        'value'  => $model->finish_date,
        'dateFormat' => 'MM/dd/yyyy',
    ]) ?>


    <?= $form->field($model, 'gamers')->textInput() ?>

    <?= $form->field($model, 'number')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
