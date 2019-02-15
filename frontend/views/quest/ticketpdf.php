<?php
/**
 * Created by PhpStorm.
 * User: mistersecret
 * Date: 29/06/15
 * Time: 18:42
 */
if(is_array($address)){
    foreach($address as $sitem){
        $ItemTitle = $sitem['title'];
        $ItemAddress=$sitem['address'];
    }
}
?>

<div class="order-container">
    <div class="order">
        <img class="order__images" alt='' src="http://tacticgame.es/athems/images/tickets.png">
        <p class="order__title"><?=Yii::t('app','THANK YOU')?> <br><span class="order__descript"><?=Yii::t('app','you have successfully purchased tickets')?></span></p>
    </div>

    <div class="ticket__inner">

        <div class="ticket-secondary">
            <div class="ticket__item"><?=strtoupper(Yii::t('app','Ticket number'))?>: <strong class="ticket__number"><?=$dataitems['code']?></strong></div>
            <div class="ticket__item ticket__date"><?=strtoupper(Yii::t('app','Date'))?>: <?=date('d/m/Y', strtotime($dataitems['date']));?></div>
            <div class="ticket__item ticket__time"><?=strtoupper(Yii::t('app','Time'))?>: <?=$dataitems['time']?></div>
            <div class="ticket__item"><?=strtoupper(Yii::t('app','Gamers'))?>: <span class="ticket__cinema"><?=$dataitems['min'].'-'.$dataitems['max']?></span></div>
            <div class="ticket__item"><?=strtoupper(Yii::t('app','Duration'))?>: <span class="ticket__hall"><?=$dataitems['duration']?></span></div>
            <div class="ticket__item ticket__price"><?=strtoupper(Yii::t('app','Price'))?>: <strong class="ticket__cost"><?=$dataitems['amount']?> €</strong></div>
        </div>

        <div class="ticket-primery">
            <div class="ticket__item ticket__item--primery ticket__film"><?=strtoupper(Yii::t('app','QUEST'))?><br><strong class="ticket__movie"><?=$dataitems['title']?></strong></div>
                        <div class="ticket__item" style="margin-top: 15px;"><strong><?=strtoupper(Yii::t('app','Address'))?></strong>: <span class="ticket__place ticket__movie"><?=$ItemTitle?></span>
                        <br>
                            <span class="ticket__movie"><?=$ItemAddress?></span>
                        </div>
        </div>


    </div>

</div>

