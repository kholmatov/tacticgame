<?php
/* @var $this yii\web\View */
#use dosamigos\fileupload\FileUploadUI;
$this->title = 'Tacticgame.es';
?>
<div class="site-index">

    <div class="jumbotron">
        <p>
            <a class="btn btn-lg btn-primary" href="/admin/items"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> <?=Yii::t('app','Item')?></a>
            <a class="btn btn-lg btn-primary" href="/admin/sections"><span class="glyphicon glyphicon-fire" aria-hidden="true"></span> <?=Yii::t('app','Sections')?></a>
            <a class="btn btn-lg btn-primary" href="/admin/position"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> <?=Yii::t('app','Position')?></a>
            <a class="btn btn-lg btn-primary" href="/admin/grafic"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> <?=Yii::t('app','Grafic')?></a>
            <a class="btn btn-lg btn-primary" href="/admin/transaction"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> <?=Yii::t('app','Transaction')?></a>
            <a class="btn btn-lg btn-primary" href="/admin/order"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> <?=Yii::t('app','Orders')?></a>
        </p>
    </div>

    <div class="body-content" style="text-align: center">

        <img src="/images/rmbp.png">

    </div>

</div>
