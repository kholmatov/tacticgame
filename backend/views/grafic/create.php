<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Grafic */

$this->title = Yii::t('app', 'Create Grafic');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Grafics'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grafic-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sections' => $sections
    ]) ?>

</div>
