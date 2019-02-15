<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CertificatSection */

$this->title = Yii::t('app', 'Create Certificat Section');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Certificat Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="certificat-section-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
