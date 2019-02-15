<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

$this->title = Yii::t('app','Contact');
$this->params['breadcrumbs'][] = Yii::t('app','Contact');
?>
<!-- Main content -->
<section class="container">
    <h2 class="page-heading"><?=Yii::t('app','Contact')?></h2>
    <div class="contact">
        <p class="contact__title"><?=Yii::t('app','You have any questions or need help,')?><br><span class="contact__describe"><?=Yii::t('app','don’t be shy and contact us')?></span></p>
        <span class="contact__mail">info@tacticgame.es</span>
        <span class="contact__tel">+34 934 63 52 21</span>
    </div>
</section>

<div class="contact-form-wrapper">
    <div class="container">
        <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
            <?php $form = ActiveForm::begin(['id' => 'contact-form','options' => [
                'class'=>'form row'
            ]]); ?>
            <p class="form__title"><?=Yii::t('app','Drop us a line')?></p>
                <div class="col-sm-6">
                    <input type='text' placeholder='<?=Yii::t('app','Name')?>' name='user-name' class="form__name">
                </div>
                <div class="col-sm-6">
                    <input type='email' placeholder='<?=Yii::t('app','Email')?>' name='user-email' class="form__mail">
                </div>
                <div class="col-sm-12">
                    <textarea placeholder="<?=Yii::t('app','Message')?>" name="user-message" class="form__message"></textarea>
                </div>
                <button type="submit" class='btn btn-md btn--danger'>send message</button>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="site-contact" style="display: none">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
    </p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                <?= $form->field($model, 'name') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'subject') ?>
                <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>
                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
