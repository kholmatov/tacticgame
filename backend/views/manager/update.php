<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserAdmin */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'User Admin',
]) . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Admins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-admin-update">

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
