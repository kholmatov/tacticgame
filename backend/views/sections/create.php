<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\Sections */

$this->title = Yii::t('app', 'Create Sections');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="sections-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?=$this->render('_form', [
        'model' => $model,
        'position' => $position
    ])?>
</div>
