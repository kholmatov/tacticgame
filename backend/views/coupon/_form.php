<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Coupon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <div style="margin-bottom:20px;">
    <?php
        $sectArr = Array('-1');
        if($model->section_id != ""){
            $sectArr = explode(",",$model->section_id);
        }
    ?>
     <?=$form->field($model, 'section_id')->checkboxList($srows,
                [
                    'item'=>function ($index, $label, $name, $checked, $value)use ($sectArr){
                        $chkd =" "; if(in_array($value, $sectArr)) $chkd = " checked ";
                        return '<div class="col-md-12">
                                         <input type="checkbox" name="sectionid[]" '.$chkd.' value="'.$value.'"/> '.$label.'
                                    </div>';
                    }
                ]

            );

        ?>
        <div style="clear:both"></div>
    </div>

    <?= $form->field($model, 'start')->widget(\yii\jui\DatePicker::classname(), [
        //'language' => 'ru',
        'value'  => $model->start,
        'dateFormat' => 'MM/dd/yyyy',
    ]) ?>

    <?= $form->field($model, 'end')->widget(\yii\jui\DatePicker::classname(), [
        //'language' => 'ru',
        'value'  => $model->end,
        'dateFormat' => 'MM/dd/yyyy',
    ]) ?>

    <?= $form->field($model, 'discount')->textInput(['maxlength' => 255,'style'=>'width:100px;']) ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => 255,'style'=>'width:100px;','readonly' => !$model->isNewRecord]) ?>
    <?= $form->field($model, 'count')->textInput(['maxlength' => 255,'style'=>'width:100px;']) ?>
    <?=$form->field($model, 'status')
        ->checkbox([
            'label' => 'Status',
            'labelOptions' => [

            ],

        ]);?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
