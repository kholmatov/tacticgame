<?php

//use yii\helpers\Html;
use yii\base\BaseJson;
//use yii\helpers\Url;




/* @var $this yii\web\View */
/* @var $model app\models\Sections */
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quest'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//TooltipAsset
?>
<!-- Stylesheets -->
<link rel="stylesheet" href="/athems/ladda-bootstrap/dist/ladda-themeless.min.css">
<!-- jQuery UI -->
<link href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet"
      xmlns="http://www.w3.org/1999/html">
<!-- Swiper slider -->
<link href="http://cdn.tacticgame.es/css/external/idangerous.swiper.css" rel="stylesheet" />

<!-- Magnific-popup -->
<link href="http://cdn.tacticgame.es/css/external/magnific-popup.css" rel="stylesheet" />
<link href="http://cdn.tacticgame.es/creditcard/creditcardjs-v0.10.12.min.css" rel="stylesheet" />
<!-- Main content -->
<section class="container">
<div class="col-sm-12">
	<div class="movie">
    <h2 class="page-heading"><?=Yii::t('app','BONO REGALO')?></h2>

    <div class="movie__info">
        <div class="col-sm-5 col-md-5 movie-mobile">
            <div class="movie__images">
                <?php
                 echo'<img style="width:100%" src="/../upload'.$model->photo.'">';
                ?>
            </div>
        </div>

        <div class="col-sm-7 col-md-7">
            <h1 style="margin-top: -5px"><?=$model->title?></h1>
            <p><?=$model->text?></p>
        </div>
    </div>

    <div class="clearfix"></div>

</div>
</div>
</section>

<div class="clearfix"></div>

<div class="contact-form-wrapper">
    <div class="container">



            <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
            <form id='certificat-form' class="form row" method='post' novalidate="" action="./certorder">
                <p class="form__title"><?=Yii::t('app','COMPRAR BONO REGALO')?></p>
                <div class="col-sm-12">

                <?php
                $gamers = explode('|', $model->gamers);

                if(count($gamers) > 0){

                        echo'<div class="col-sm-12" style="text-align:left">';
                            $rs_html = '<div class="tarif-ul" data-error="'.Yii::t('app','Please select a tariff!').'"
                              style="padding:5px 15px 5px 15px;margin: 1em 0 1em 0;border-radius:.75em;border:1px solid #dbdbdb;background:#fafafa">
                                        <ul>';
                            foreach ($gamers as $rows) {
                                $gamer = explode('-', $rows);
                                $rs_html .= "<li class='tariff-li'>";
                                $rs_html .= "<input type='radio' id='tariflist' name=\"tariflist\" value='$gamer[0]'>";
                                $rs_html .= " <i class='fa fa-user'></i> ".$gamer[0];
                                $rs_html .= " - <span class='tariff-color'>" . $gamer[1] . "€</li>";
                            }

                            $rs_html .= '</ul></div>';
                            echo $rs_html;

                        echo'</div>';

                }
                ?>

                <div class="col-sm-12">
                    <input type='text' placeholder='<?=Yii::t('app','email')?>' data-emailerror="<?=Yii::t('app','Error! Wrong email!')?>" name='user-email' class="form__mail">
                </div>
                <div class="col-sm-12">
                    <input type='text' placeholder='<?=Yii::t('app','phone')?>' data-phoneerror="<?=Yii::t('app','Error! Wrong phone!')?>"name='user-phone' class="form__mail">
                </div>
                <div class="col-sm-12">
                    <img alt="" src="/../athems/images/payment/pay6.png">
                    <img alt="" src="/../athems/images/payment/pay7.png">
                </div>
                </div>

                <div class="col-sm-6 payment" style="display:none">
                        <center>
                            <div class="ccjs-card" style="display:block;background:#fafafa;">
                        <label class="ccjs-number">
                            <?=Yii::t('app','Card Number')?>
                            <input name="card-number" class="ccjs-number" placeholder="•••• •••• •••• ••••" data-error="Card Number Error!">
                        </label>

                        <label class="ccjs-csc">
                            <?=Yii::t('app','Security Code')?>
                            <input name="csc" class="ccjs-csc" placeholder="•••" data-error="Security Code Error!">
                        </label>

                        <button type="button" class="ccjs-csc-help">?</button>

                        <label class="ccjs-name">
                            <?=Yii::t('app','Name on Card')?>
                            <input name="name" class="ccjs-name" data-error="Name on Card Error!">
                        </label>

                        <fieldset class="ccjs-expiration" data-error="Expiration Error!">
                            <legend><?=Yii::t('app','Expiration')?></legend>
                            <select name="month" class="ccjs-month">
                                <option selected disabled><?=Yii::t('app','MM')?></option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>

                            <select name="year" class="ccjs-year">
                                <option selected disabled><?=Yii::t('app','YY')?></option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                            </select>
                        </fieldset>


                        <select name="card-type" class="ccjs-hidden-card-type">
                            <option value="mastercard" class="ccjs-mastercard">MasterCard</option>
                            <option value="visa" class="ccjs-visa">Visa</option>
                        </select>
                    </div>
                        </center>
                </div>

                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                <input type="hidden" name="id" value="<?=$model->id?>"/>
                <div class="clearfix"></div>
                <br>
                <button type="submit" class='btn btn-md btn--danger'onclick="return false;"id="lstatus"><?=Yii::t('app','BUY')?></button>
                <div class="contact">
                    <span class="contact__tel" style="margin-top: 16px;">+34 934 63 52 21</span>
                    <span class="contact__mail">info@tacticgame.es</span>

                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript-->
<!-- jQuery 1.9.1-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/external/jquery-1.10.1.min.js"><\/script>')</script>
<!-- Migrate -->
<script src="/athems/js/external/jquery-migrate-1.2.1.min.js"></script>
<!-- Bootstrap 3-->
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

<!-- Mobile menu -->
<script src="/athems/js/jquery.mobile.menu.js"></script>
<!-- Select -->
<script src="/athems/js/external/jquery.selectbox-0.2.min.js"></script>

<!-- Form element -->
<script src="/athems/js/external/form-element.js"></script>
<!-- Form validation -->
<script src="/athems/js/form.js"></script>

<!-- Custom -->
<script src="/athems/js/custom.js"></script>

<!--ProgressBar Btn -->
<script src="/athems/ladda-bootstrap/dist/spin.min.js"></script>
<script src="/athems/ladda-bootstrap/dist/ladda.min.js"></script>
<!--Credit Card-->
<script src="/athems/creditcard/creditcardjs-v0.10.12.min.js"></script>

