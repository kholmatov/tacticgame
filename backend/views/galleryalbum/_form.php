<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use zxbodya\yii2\galleryManager\GalleryManager;

/* @var $this yii\web\View */
/* @var $model app\models\Galleryalbum */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gallery-album-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
<?php
    $sectionsList = ArrayHelper::map($sections,'id','title');
?>
    <?= $form->field($model, 'id_section')->dropDownList($sectionsList,['prompt'=>Yii::t('app','Choose a Sections')]);?>


    <?= $form->field($model, 'active')->checkbox() ?>
    <?php
    if($model->isNewRecord){
        $model->created = date('m/d/Y');
    }else{
        $model->created =  date('m/d/Y', $model->created);
    }
    ?>

    <?= $form->field($model, 'created')->widget(\yii\jui\DatePicker::classname(), [
        //'language' => 'ru',
        'value'  => $model->created,
        'dateFormat' => 'MM/dd/yyyy',
    ]) ?>

    <?php
    if ($model->isNewRecord) {
        echo 'Can not upload images for new record';
    } else {
        echo GalleryManager::widget(
            [
                'model' => $model,
                'behaviorName' => 'galleryBehavior',
                'apiRoute' => 'galleryalbum/galleryApi'
            ]
        );
    }

    //    foreach($model->getBehavior('galleryBehavior')->getImages() as $image) {
    //        echo Html::img($image->getUrl('medium'));
    //    }
    ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
