<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Certification */

$this->title = Yii::t('app', 'Create Certification');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Certifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="certification-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
