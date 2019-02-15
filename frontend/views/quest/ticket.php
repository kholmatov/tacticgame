<?php
/**
 * Created by PhpStorm.
 * User: mistersecret
 * Date: 29/06/15
 * Time: 18:42
 */
use yii\helpers\Url;
$this->title = $dataitems['code'];
if(is_array($address)){
    foreach($address as $sitem){
        $ItemTitle = $sitem['title'];
        $ItemAddress=$sitem['address'];
    }
}

?>
<link rel="stylesheet" href="http://tacticgame.es/athems/css/printer.css" type="text/css" media="print" >
<section class="container" style="margin-top:100px" id="printarea">
    <div class="order-container">
        <div class="order">
            <img class="order__images" alt='' src="/../athems/images/tickets.png">
            <p class="order__title"><?=Yii::t('app','THANK YOU')?> <br><span class="order__descript"><?=Yii::t('app','you have successfully purchased tickets')?></span></p>
        </div>

        <div class="ticket">
            <div class="ticket-position">
                <div class="ticket__indecator indecator--pre"><div class="indecator-text pre--text">tacticgame.es</div> </div>
                <div class="ticket__inner">

                    <div class="ticket-secondary">
                        <span class="ticket__item"><?=Yii::t('app','Ticket number')?> <strong class="ticket__number"><?=$dataitems['code']?></strong></span>
                        <span class="ticket__item ticket__date"><?=date('d/m/Y', strtotime($dataitems['date']));?></span>
                        <span class="ticket__item ticket__time"><?=$dataitems['time']?></span>
                        <span class="ticket__item"><?=Yii::t('app','Gamers')?>: <span class="ticket__cinema"><?=$dataitems['min'].'-'.$dataitems['max']?></span></span>
                        <span class="ticket__item"><?=Yii::t('app','Duration')?>: <span class="ticket__hall"><?=$dataitems['duration']?></span></span>
                        <span class="ticket__item ticket__price"><?=Yii::t('app','Price')?>: <strong class="ticket__cost"><?=$dataitems['amount']?> €</strong></span>
                    </div>

                    <div class="ticket-primery">
                        <span class="ticket__item ticket__item--primery ticket__film"><?=Yii::t('app','QUEST')?><br><strong class="ticket__movie"><?=$dataitems['title']?></strong></span>
                        <span class="ticket__item"><strong><?=Yii::t('app','Address')?></strong>: <span class="ticket__place ticket__movie"><?=$ItemTitle?></span>
                        <br>
                            <span class="ticket__movie"><?=$ItemAddress?></span>
                        </span>
                    </div>


                </div>
                <div class="ticket__indecator indecator--post"><div class="indecator-text post--text">tacticgame.es</div></div>
            </div>
        </div>

        <div class="ticket-control">
            <a href="<?=Url::toRoute(['/quest/ticketpdf', 'code' => $dataitems['code']])?>" class="watchlist list--download">Download</a>
            <a href="javascript:void(0)" onclick="printDiv('printarea')" class="watchlist list--print">Print</a>
        </div>

    </div>
</section>

<div class="clearfix"></div>

<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        //document.getElementById(divName).style.height = "800px";
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>