<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\UserAdmin */

$this->title = Yii::t('app', 'Create User Admin');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Admin'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-admin-create">

        <div class="widget">
            <div class="widget-header">
                <i class="icon-user"></i>
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="widget-content">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
            </div>
        </div>

</div>
<script>
    $( document ).ready(function() {
        $("form :input").attr("autocomplete", "off");
    });
</script>