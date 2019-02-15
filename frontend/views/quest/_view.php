<?php

//use yii\helpers\Html;

use yii\base\BaseJson;
//use yii\helpers\Url;


//Maps
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\layers\BicyclingLayer;
use dosamigos\google\maps\overlays\InfoWindow;


/* @var $this yii\web\View */
/* @var $model app\models\Sections */
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quest'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$model_array = unserialize($model->photo);
$this->registerMetaTag(['name' => 'og:image', 'content' =>'http://tacticgame.es/upload'.$model_array[0]['avatar']]);
//<meta property="og:image" content="http://example.com/ogp.jpg" />
//<meta property="og:image:secure_url" content="https://secure.example.com/ogp.jpg" />
//<meta property="og:image:type" content="image/jpeg" />
//<meta property="og:image:width" content="400" />
//<meta property="og:image:height" content="300" />
//TooltipAsset
?>
<!-- Stylesheets -->
<!-- jQuery UI -->
<link href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet"
      xmlns="http://www.w3.org/1999/html">
<!-- Swiper slider -->
<link href="http://cdn.tacticgame.es/css/external/idangerous.swiper.css" rel="stylesheet" />

<!-- Magnific-popup -->
<link href="http://cdn.tacticgame.es/css/external/magnific-popup.css" rel="stylesheet" />

<link href="http://cdn.tacticgame.es/creditcard/creditcardjs-v0.10.12.min.css" rel="stylesheet" />

<?php if(!$dev):?>
    <center>
       <?php
        echo'<div class="myavatar" style="background-image:url(/../upload'.$model->avatar.');">';
       ?>
        <div class="mytext">
            <section class="container">
                <div class="col-sm-12">
                    <div class="col-sm-6 col-md-6">
                        <h1 class="my-avatar-head"><?=$model->title?></h1>
                        <?php
                        $mytext = explode("<hr/>",$model->shorttext);
                        ?>
                     <p><?=$mytext[0]?></p>

                    </div>
                    <div class="col-sm-6 col-md-6">
                        <p style="color: #fedb33;text-align: right">

                            <span><i class="fa fa-clock-o"></i> <?=$model->duration?></span>&nbsp;&nbsp;&nbsp;
                            <span><i class="fa fa-users"></i> <?=$model->min.' - '.$model->max?> <?=Yii::t('app','Gamer')?></span>&nbsp;&nbsp;&nbsp;
                            <span><i class="fa fa-star"></i> <?=$model->rating?></span>
                        </p>
                        <p><?php
                            if(isset($mytext[1])) echo $mytext[1]
                           ?>
                        </p>
                    </div>
                </div>
            </section>
        </div>
        </div>
    </center>
<?php endif?>

<!-- Main content -->
<section class="container">

<div class="col-sm-12">
<?php if($dev):?>
<div class="movie">

    <h2 class="page-heading"><?=$model->title?></h2>


    <div class="movie__info">

        <div class="col-sm-6 col-md-6 movie-mobile">
            <div class="movie__images">
                <span class="movie__rating"><?=$model->rating?></span>
                <?php
                $model_array = unserialize($model->photo);
                echo'<img src="/../upload'.$model_array[0]['avatar'].'">';
                ?>
            </div>
        </div>

        <div class="col-sm-6 col-md-6">


            <p><?=$model->shorttext?></p>
            <p style="color: #fe505a">
            <span><i class="fa fa-clock-o"></i> <?=$model->duration?></span><br>
            <span><i class="fa fa-users"></i> <?=$model->min.' - '.$model->max?> <?=Yii::t('app','Gamer')?></span><br>
            <span><i class="fa fa-star"></i> <?=$model->rating?></span>
            </p>
        </div>

    </div>


    <div class="clearfix"></div>


    <h2 class="page-heading"><?=Yii::t('app','photos')?></h2>

    <div class="movie__media">
        <div class="movie__media-switch">
            <?php
            if($model->photo){
                $model_array = unserialize($model->photo);
                echo'<a href="#" class="watchlist list--photo" data-filter="media-photo">'.count($model_array).' '.Yii::t('app','photos');
	            echo '</a>';
            }
            if($model->youtube){
                $list = explode(";",$model->youtube);
                echo'<a href="#" class="watchlist list--video" data-filter="media-video">'.count($list).' '.Yii::t('app','videos').'</a>';
            }
            ?>
<!--            <a href="movie-page-full.html#" class="watchlist list--video" data-filter='media-video'>10 videos</a>-->
        </div>

        <div class="swiper-container">
            <div class="swiper-wrapper">

                <?php

                if($model->photo){
                    foreach($model_array as $ims){
                        echo'<div class="swiper-slide media-photo">';
                          echo'<a href="/../upload'.$ims['normal'].'" class="movie__media-item">';
                          echo'<img alt="" src="/../upload'.$ims['preview'].'">';
                          echo'</a>';
                        echo'</div>';
                    }
                 }

                if($model->youtube){
                    $list = explode(";",$model->youtube);
                    foreach($list as $row){
                        //$html.='<img src="http://img.youtube.com/vi/'.trim($row).'/mqdefault.jpg" class="img-thumbnail" style="margin-right:5px;">';
                        echo'<div class="swiper-slide media-video swiper-slide-visible">

                    	 	<a href="https://www.youtube.com/watch?v='.trim($row).'" class="movie__media-item">
                    	 	<span><i class="fa fa-video-camera"></i></span>
                    			<img alt="" src="http://img.youtube.com/vi/'.trim($row).'/mqdefault.jpg">
                    		</a>
                    	</div>';

                    }
                }
                ?>

            </div>
        </div>

    </div>

</div>
<?php endif;?>

<h2 class="page-heading"><?=Yii::t('app','showtime & tickets')?></h2>

<div class="choose-container">

    <div class="adress col-sm-10">
        <span class="adress__marker"><i class="fa fa-compass"></i> <?=Yii::t('app','Address')?>:</span>
        <label class="adress_desc"><?php
            //echo $address[0]['title'];
            echo $address[0]['address'];
            ?>
        </label>
    </div>

    <div class="clearfix"></div>

    <div class="time-select">
        <?php

        //echo Yii::t('app','Today is {0, date,Y-m-d H:i:s}',time());
         $n =count($dataitems);
         $i=0;
         foreach($dataitems as $key => $item){
             $i++;
             if($i==1)
                $groupClass=' group--first';
             elseif($i==$n)
                 $groupClass=' group--last';
             else
                 $groupClass='';


             $full = $item['full'];

             echo '<div class="time-select__group'.$groupClass.'">';
             echo'<div class="col-sm-4">
                    <p class="time-select__place">'.$full.'</p>
                  </div>';
             unset($item['full']);

             echo'<ul class="col-sm-8 items-wrap">';
             foreach($item as $row){
                if($row['status']==1)
                    echo'<li class="time-select__item disable">'.$row['time'].'</li>';
                else {
                    echo '<li class="time-select__item show"
                            data-toggle="tooltip"
                            title="' . $row['price'] . ' ' . $row['currency'] . '"
                            data-time="' . $row['time'] . '"
                            data-price="' . $row['price'] . '"
                            data-currency="' . $row['currency'] . '"
                            data-date="' . $row['date'] . '"
                            data-sid="' . $row['section_item_id'] . '"
                            data-full="' . $full . '"
                            data-short="' . $row['short'] . '"
                            data-sale = "true"
                            >';
                    if ($row['sale']):
                        echo '<a class="mysale" href="#">
                            ' . $row['time'] . '
                                <span>' . yii::t ('app', 'SALE') . '</span>
                            </a>';

                         else:
                         echo $row['time'];
                        endif;

                    echo '</li>';

                }
             }
             echo'</ul>';
         echo'</div>';

         }

        ?>

        <?php
$js = <<< 'SCRIPT'
/* To initialize BS3 tooltips set this below */
$(function () {
    $("[data-toggle='tooltip']").tooltip();
});
/* To initialize BS3 popovers set this below */
//$(function () {
//    $("[data-toggle='popover']").popover();
//});
SCRIPT;
// Register tooltip/popover initialization javascript
//$this->registerJs($js);

?>

</div>

    <!-- hiden maps with multiple locator-->
        <?php
            $coord = new LatLng(['lat' => $address[0]['latitude'], 'lng' => $address[0]['longitude']]);
            $map = new Map([
                'center' => $coord,
                'zoom' => 16,
                'scrollwheel'=> false,
            ]);
            //$map->containerOptions=['class'=>'mapclass'];
            $map->height='400';
            $map->width='100%';

            $coord = new LatLng(['lat' => $address[0]['latitude'], 'lng' => $address[0]['longitude']]);
            $marker = new Marker([
                'position' => $coord,
                'title' => $address[0]['title'],
                'icon'=>'/../tacticgamepoint.png',

            ]);

            // Provide a shared InfoWindow to the marker
            $marker->attachInfoWindow(
                new InfoWindow([
                    'content' => '<b>'.$address[0]['title'].'</b><p>'.$address[0]['address'].'</p>'
                ])
            );

            // Add marker to the map
            $map->addOverlay($marker);

            //========================================================
            // Lets show the BicyclingLayer :)
            $bikeLayer = new BicyclingLayer(['map' => $map->getName()]);

            // Append its resulting script
            $map->appendScript($bikeLayer->getJs());

            // Display the map -finally :)
            echo $map->display();


        ?>
</div>
</div>
<div class="col-sm-12" style="margin-top: 50px;">
    <div id="disqus_thread"></div>
</div>
<script type="text/javascript">
    /* * * CONFIGURATION VARIABLES * * */
    var disqus_shortname = 'tacticegamees';
    var disqus_identifier = '<?=$model->id?>';

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>

<script type="text/javascript">
    /* * * CONFIGURATION VARIABLES * * */
    var disqus_shortname = 'tacticegamees';
    var disqus_identifier = '<?=$model->id?>';
    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function () {
        var s = document.createElement('script'); s.async = true;
        s.type = 'text/javascript';
        s.src = '//' + disqus_shortname + '.disqus.com/count.js';
        (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
    }());
</script>

</section>

<div class="clearfix"></div>
<link rel="stylesheet" href="/athems/ladda-bootstrap/dist/ladda-themeless.min.css">
<!-- JavaScript-->
<!-- jQuery 1.9.1-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/athems/js/external/jquery-1.10.1.min.js"><\/script>')</script>
<!-- Migrate -->
<script src="/athems/js/external/jquery-migrate-1.2.1.min.js"></script>
<!-- jQuery UI -->
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<!-- Bootstrap 3-->
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

<!-- Mobile menu -->
<script src="/athems/js/jquery.mobile.menu.js"></script>
<!-- Select -->
<script src="/athems/js/external/jquery.selectbox-0.2.min.js"></script>

<!-- Stars rate -->
<script src="/athems/js/external/jquery.raty.js"></script>
<!-- Swiper slider -->
<script src="/athems/js/external/idangerous.swiper.min.js"></script>
<!-- Magnific-popup -->
<script src="/athems/js/external/jquery.magnific-popup.min.js"></script>



<!-- Form element -->
<script src="/athems/js/external/form-element.js"></script>
<!-- Form validation -->
<script src="/athems/js/form.js"></script>

<!-- Custom -->
<script src="/athems/js/custom.js"></script>

<!--ProgressBar Btn -->
<script src="/athems/ladda-bootstrap/dist/spin.min.js"></script>
<script src="/athems/ladda-bootstrap/dist/ladda.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        <?php
        if($dev){
         echo "init_MoviePage();";
         echo "init_MoviePageFull();";
        }
        ?>

        $("[data-toggle='tooltip']").tooltip();
        $('.items-wrap .show').on('click',function(){
            var short = $(this).data('short');
            var time = $(this).data('time');
            var date = $(this).data('date');
            var sid =$(this).data('sid');
            var price = $(this).data('price');
            var currency = $(this).data('currency');
            $('.booking-ticket').text(short+', '+time);
            $('.booking-cost').text(price+' '+currency);

            $('#quest-time').val(time);
            $('#quest-date').val(date);
            $('#quest-section').val(sid);
            $('#quest-price').val(price);


            var modalContainer = $('#my-modal');
            var modalBody = modalContainer.find('.modal-body');
            //modalBody.html('test');
            modalContainer.modal({show:true});
        });

        $("input[name=payment_type]" ).on( "click", function() {
            if($( "input[name=payment_type]:checked" ).val()=='credit_card'){
                $(".ccjs-card").fadeIn();
            }else{
                $(".ccjs-card").fadeOut();
            }
        });

        var Complete=false;
        $('#quest-button').on('click',function(){

            if(!Complete){
                var isError = false;
                var _payment_type=$( "input[name=payment_type]:checked" ).val();
	            console.log(_payment_type);
                if(_payment_type=='credit_card'){
                    if($("input.ccjs-number-formatted").val().length < 19){
                        $("input.ccjs-number-formatted").addClass("input-error");
                        isError = true;
                    }

                    if($("input.ccjs-csc").val().length==0){
                        $("input.ccjs-csc").addClass("input-error");
                        isError = true;
                    }

                    if($("input.ccjs-name").val().length==0){
                        $("input.ccjs-name").addClass("input-error");
                        isError = true;
                    }

                    if($(".ccjs-month option:selected" ).text()=='MM' || $(".ccjs-year option:selected" ).text()=='YY'){
                        $(".ccjs-expiration").addClass("ccjs-error");
                        isError = true;
                    }
                }

                // Проверяем все поля указанной формы на пустоту
                $("#contact-info .request").each(function(){
                    if ($(this).val().length == 0) {
                        $(this).addClass("input-error");
                        isError = true;
                    }
                });



                if(!isError) {
                    var csrfToken = $('meta[name="csrf-token"]').attr("content");
                        var fields = {
                            client_email: $('#quest-mail').val(),
                            client_phone:$('#quest-tel').val(),
                            client_section:$('#quest-section').val(),
                            client_time:$('#quest-time').val(),
                            client_date:$('#quest-date').val(),
                            client_price:$('#quest-price').val(),
                            quest_title: $('#quest-title').val(),
                            //Payment option
                            payment_type: _payment_type,
	                        credit_type: $(".ccjs-type-read-only").html(),
                            credit_number: $("input.ccjs-number-formatted").val(),
                            credit_csc: $("input.ccjs-csc").val(),
                            credit_name:$("input.ccjs-name").val(),
                            credit_month:$(".ccjs-month option:selected" ).text(),
                            credit_year:$(".ccjs-year option:selected" ).text(),
                            id_source: 'order',
                            _csrf : csrfToken
                        };
                    var l = Ladda.create(this);
                    l.start();
                    sendRequest(fields,_payment_type);



                    //$(".modal").fadeIn("fast");
                    Complete = true;
                    setInterval(function () {Complete = false;}, 2000);
                }
            }
        });


        // Функция, отправляющая заявку
        function sendRequest(fields,payment_type){



            $.post("/quest/orders", fields, function(json){
                var obj = $.parseJSON(json);
	            if(payment_type!='lacaixa') {
                    if (obj.url != "") {
                        window.location = obj.url;
                        $(".modal .close").click();
                        Complete = false;
                    }

                }else{
                    my_form = document.createElement('FORM');
                    my_form.name = 'myForm';
                    my_form.method = 'POST';
                    my_form.action = obj.form_url;
                    my_tb=document.createElement('INPUT');
                    my_tb.type='TEXT';
                    my_tb.name='Ds_SignatureVersion';
                    my_tb.value=obj.ds_signatureversion;
                    my_form.appendChild(my_tb);
                    my_tb=document.createElement('INPUT');
                    my_tb.type='TEXT';
                    my_tb.name='Ds_MerchantParameters';
                    my_tb.value=obj.ds_merchantparameters;
                    my_form.appendChild(my_tb);
                    my_tb=document.createElement('INPUT');
                    my_tb.type='TEXT';
                    my_tb.name='Ds_Signature';
                    my_tb.value=obj.ds_signature;
                    my_form.appendChild(my_tb);
                    document.body.appendChild(my_form);
                    my_form.submit();
                }
            });
        }

        $('input,textarea').focus(function(){
            $(this).data('placeholder',$(this).attr('placeholder'))
            $(this).attr('placeholder','');
            $(this).removeClass('input-error');
        });
        $('input,textarea').blur(function(){
            $(this).attr('placeholder',$(this).data('placeholder'));
        });

    });
</script>





<!-- Modal "Записаться на квест" -->
<div class="modal fade bs-example-modal-lg" id="my-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title page-heading" id="gridSystemModalLabel" >
                    <?=$model->title?>
                </h4>
            </div>
            <div class="modal-body">

                <form id="contact-info" method="post" novalidate="" class="form contact-info">

                    <ul class="book-result">
                        <li class="book-result__item"><?=Yii::t('app','Beginning session')?>: <span class="book-result__count booking-ticket"></span></li>
<!--                        <li class="book-result__item">One item price: <span class="book-result__count booking-price">$20</span></li>-->
                        <li class="book-result__item"><?=Yii::t('app','For payment')?>: <span class="book-result__count booking-cost"></span></li>
                    </ul>

                    <h2 class="page-heading"><?=Yii::t('app','Choose payment method')?></h2>
                    <div class="payment">
                        <?php /*
                        <label class="radio-inline" style="display:none">
                            <input type="radio" name="payment_type" id="inlineRadio1" value="paypal" checked>
	                        <img alt="" src="/../athems/images/payment/pay1.png">
                        </label>

                        <label class="radio-inline" style="display:none">
                            <input type="radio" name="payment_type" id="inlineRadio2" value="credit_card">
                            <img alt="" src="/../athems/images/payment/pay6.png">
                            <img alt="" src="/../athems/images/payment/pay7.png">
                        </label>
                        */
                        ?>

                        <label class="radio-inline">
                            <input type="radio" name="payment_type" checked id="inlineRadio4" value="none"> <?=Yii::t('app','None')?>
                        </label>

                        <?php if(!$dev):?>
                        <label class="radio-inline">
                            <input type="radio" name="payment_type" id="inlineRadio5" value="lacaixa">
                            <img alt="" src="/../athems/images/payment/pay6.png">
                            <img alt="" src="/../athems/images/payment/pay7.png">
                        </label>
                        <?php endif;?>
                        <div class="ccjs-card">
                            <label class="ccjs-number">
                                <?=Yii::t('app','Card Number')?>
                                <input name="card-number" class="ccjs-number" placeholder="•••• •••• •••• ••••">
                            </label>

                            <label class="ccjs-csc">
                                <?=Yii::t('app','Security Code')?>
                                <input name="csc" class="ccjs-csc" placeholder="•••">
                            </label>

                            <button type="button" class="ccjs-csc-help">?</button>

                            <label class="ccjs-name">
                                <?=Yii::t('app','Name on Card')?>
                                <input name="name" class="ccjs-name">
                            </label>

                            <fieldset class="ccjs-expiration">
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
<!--                                <option value="amex" class="ccjs-amex">American Express</option>-->
<!--                                <option value="discover" class="ccjs-discover">Discover</option>-->
                                <option value="mastercard" class="ccjs-mastercard">MasterCard</option>
                                <option value="visa" class="ccjs-visa">Visa</option>
<!--                                <option value="diners-club" class="ccjs-diners-club">Diners Club</option>-->
<!--                                <option value="jcb" class="ccjs-jcb">JCB</option>-->
                                <!--<option value="laser" class="laser">Laser</option>-->
                                <!--<option value="maestro" class="maestro">Maestro</option>-->
                                <!--<option value="unionpay" class="unionpay">UnionPay</option>-->
                                <!--<option value="visa-electron" class="visa-electron">Visa Electron</option>-->
                                <!--<option value="dankort" class="dankort">Dankort</option>-->
                            </select>


                        </div>
                    </div>


                    <h2 class="page-heading"><?=Yii::t('app','Contact information')?></h2>


                        <div class="contact-info__field contact-info__field-mail">
                            <input type="email" id="quest-mail" name="user-mail" placeholder="<?=Yii::t('app','Your email')?>" class="form__mail request">
                        </div>
                        <div class="contact-info__field contact-info__field-tel">
                            <input type="tel" id="quest-tel" name="user-tel" placeholder="<?=Yii::t('app','Phone number')?>" class="form__mail request">
                        </div>
                    </form>


            </div>
            <div class="modal-footer progress-demo">
                <input type="hidden" id="quest-time" value="0">
                <input type="hidden" id="quest-date" value="0">
                <input type="hidden" id="quest-section" value="0">
                <input type="hidden" id="quest-price" value="0">
                <input type="hidden" id="quest-title" value="<?=$model->title?>">
                <button type="button" class="btn btn-md btn--warning btn--wide ladda-button" id="quest-button" data-style="expand-right"><?=Yii::t('app','next')?></button>
             </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--Credit Card-->
<script src="/athems/creditcard/creditcardjs-v0.10.12.min.js"></script>
<script>
    if (navigator.appVersion.indexOf("Chrome/") != -1) {
        // modify button
       $('a.mysale span').css("margin-left","-14px");
    }
</script>
