<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Sections;
use backend\models\Couponcode;

/* @var $this yii\web\View */
/* @var $model backend\models\Coupon */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coupons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php
    $sArray = explode(",", $model->section_id);
    $sections = Sections::find()->asArray()->where(['id' => $sArray])->all();
    $shtml = "";
    foreach ($sections as $row)
        $shtml .= '<span class="label label-default">'.$row['title'].'</span><br>';

    ?>
    <?php
    $code = Couponcode::find()->asArray()->where(['coupon_id' => $model->id])->all();
    $chtml = "";
    $chtmlS = "";
    $chtmlB = "";
    $cnt = 0;
    foreach ($code as $row) {
        if($row['status']) {
            $chtmlS .= "<br><s>" . $row['code'] . "</s>";
            $cnt++;
        }else{
            $chtmlB .= "<br><b>" . $row['code'] . "</b>";
        }
    }
    $chtml .= $chtmlB;
    $chtml .= "<p>--------------------------</p>";
    $chtml .= $chtmlS;
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'label' => Yii::t('app', 'Sections'),
                'value' => $shtml,
                'format' => 'raw',
            ],
            [
                'attribute' => 'start',
                'format' => ['date', 'php:F j, Y']
            ],
            [
                'attribute' => 'end',
                'format' => ['date', 'php:F j, Y']
            ],
            'discount',
            'number',
            'count',
            [
                'label' => Yii::t('app', 'Status'),
                'value' => $model->status ? Yii::t('app', 'Yes') : Yii::t('app', 'No')
            ],
            'createdate',
        ],
    ]) ?>
    <button id="button1" class="btn btn-success" onclick="CopyToClipboard('couponcode')">Copy Code <span class="badge"><?=$cnt?>/<?=$model->number?></span></button>
    <div id="couponcode" class="raw">
        <?=$chtml?>
    </div>
    <style>
        #couponcode s{
            color:#f00;
        }
        #couponcode b{
            color:#5cb85c;
        }
    </style>
<script>
    function CopyToClipboard(containerid) {
        if (document.selection) {
            var range = document.body.createTextRange();
            range.moveToElementText(document.getElementById(containerid));
            range.select().createTextRange();
            document.execCommand("Copy");

        } else if (window.getSelection) {
            var range = document.createRange();
            range.selectNode(document.getElementById(containerid));
            window.getSelection().addRange(range);
            document.execCommand("Copy");
            alert("Code copied")
        }}
</script>
</div>
