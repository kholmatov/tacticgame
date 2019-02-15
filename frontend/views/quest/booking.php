<?php
/**
 * Created by PhpStorm.
 * User: mistersecret
 * Date: 29/06/15
 * Time: 18:42
 */
use yii\helpers\Url;
$this->title = Yii::t('app','Booking');//$code;


?>
<section class="container" style="margin-top:200px;min-height: 500px" id="printarea">

    <div class="order-container">
        <div class="order">
            <img class="order__images" alt='' src="/../athems/images/tickets.png">
            <p class="order__title"><?=Yii::t('app','THANK YOU FOR YOUR BOOKING')?>
            </p>
            <span class="order__descript"><?=Yii::t('app','This confirms that your booking has been successful, you will receive an email confirmation together with full confirmation from our bookings manager.')?></span>
            <span class="order__descript">
                <?=Yii::t('app','In the meantime, if you have any questions or need further information, please contact us at')?>
                <a href="mailto:info@tacticgame.es" style="color: #fe505a">info@tacticgame.es</a>
                <br>
                <?=Yii::t('app','please check the spam')?>
            </span>
        </div>

    </div>
</section>
<div class="clearfix"></div>
