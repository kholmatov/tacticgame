<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUploadUI;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Sections */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sections-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'title_es')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'tariff')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'rating')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'shorttext')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'shorttext_es')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'youtube')->textarea(['rows' => 6]) ?>
    <?php
        //Html::activeDropDownList($model, 'postion_item_id',
        //ArrayHelper::map($sections,'id','stitle','ptitle'),[ 'prompt' => Yii::t('app','Please Select Sections'),'class' => 'form-control'])
      $positionList = ArrayHelper::map($position,'id','address','title');

    ?>

    <?= $form->field($model, 'position_id')->dropDownList($positionList,['prompt'=>Yii::t('app','Choose a Postion')]);?>

    <?php
        if($model->slide!=""){
            echo Html::img('/../upload'.$model->slide,['width'=>100]);
        }
    ?>

    <?= $form->field($model, 'file')->fileInput() ?>


    <?php
    if($model->avatar!=""){
        echo Html::img('/../upload'.$model->avatar,['width'=>100]);
    }
    ?>

    <?= $form->field($model, 'file2')->fileInput() ?>


    <?= $form->field($model, 'min')->textInput() ?>

    <?= $form->field($model, 'max')->textInput() ?>

    <?= $form->field($model, 'duration')->textInput(['maxlength' => 200]) ?>
    <?= $form->field($model, 'duration_es')->textInput(['maxlength' => 200]) ?>

    <?= $form->field($model, 'active')->checkbox() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>

<?php if(!$model->isNewRecord):?>
    <div class="sections-form">
    <?=FileUploadUI::widget([
        'model' => $model,
        'attribute' => 'photos',
        'url' => ['sections/upload', 'id' => $model->id],
        'gallery' => false,
        'fieldOptions' => [
            'accept' => 'image/*'
        ],
        'clientOptions' => [
            'maxFileSize' => 2000000
        ],
        // ...
        'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
            'fileuploadfail' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
        ],
    ]);
    ?>
</div>
<?php endif;?>

<?php
if($model->photo){
    $model_array = unserialize($model->photo);
    foreach($model_array as $ims){
        $items[] =array(
            'url' => '/../upload'.$ims['normal'],
            'src' => '/../upload'.$ims['preview'],
            'photo_id'=>$ims['photo_id'],
            'options' => array('title' => $ims['photo_id'])
        );
    }
    echo dosamigos\gallery\Gallery::widget(['items' => $items]);
}
?>
<?php
//Url::to(['photodelete', 'id' => $model->id,'photo_id'=>$photoID])
$url=Url::to(['photodelete', 'id' => $model->id]);
$script = "
$('.gallery-item .remove-photo').on('click', function(e) {
    if (confirm('Remove this image?') == true) {
       var photo_id=$(this).data('id');
       $.ajax({
        url: '$url&photo_id='+photo_id,
           success: function(data) {
              $('.'+photo_id).remove();
           }
        });
    }
    return false;
});";

$this->registerJs($script);
?>