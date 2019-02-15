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
$this->registerMetaTag(['name' => 'og:image', 'content' => 'http://tacticgame.es/upload' . $model_array[0]['avatar']]);
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
<link href="http://cdn.tacticgame.es/css/external/idangerous.swiper.css" rel="stylesheet"/>

<!-- Magnific-popup -->
<link href="http://cdn.tacticgame.es/css/external/magnific-popup.css" rel="stylesheet"/>

<link href="http://cdn.tacticgame.es/creditcard/creditcardjs-v0.10.12.min.css" rel="stylesheet"/>

<?php if (!$dev): ?>
    <center>
        <?php
        echo '<div class="myavatar" style="background-image:url(/../upload' . $model->avatar . ');">';
        ?>
        <div class="mytext">
            <section class="container">
                <div class="col-sm-12">
                    <div class="col-sm-6 col-md-6">
                        <h1 class="my-avatar-head"><?= $model->title ?></h1>
                        <?php
                        $mytext = explode("<hr/>", $model->shorttext);
                        ?>
                        <p><?= $mytext[0] ?></p>

                    </div>
                    <div class="col-sm-6 col-md-6">
                        <p style="color: #fedb33;text-align: right">

                            <span><i class="fa fa-clock-o"></i> <?= $model->duration ?></span>&nbsp;&nbsp;&nbsp;
                            <span><i
                                    class="fa fa-users"></i> <?= $model->min . ' - ' . $model->max ?> <?= Yii::t('app', 'Gamer') ?></span>&nbsp;&nbsp;&nbsp;
                            <span><i class="fa fa-star"></i> <?= $model->rating ?></span>
                        </p>
                        <p><?php
                            if (isset($mytext[1])) echo $mytext[1]
                            ?>
                        </p>
                    </div>
                </div>
            </section>
        </div>
        </div>
    </center>
<?php endif ?>

<!-- Main content -->
<section class="container">

    <div class="col-sm-12">
        <?php if ($dev): ?>
            <div class="movie">

                <h2 class="page-heading"><?= $model->title ?></h2>


                <div class="movie__info">

                    <div class="col-sm-6 col-md-6 movie-mobile">
                        <div class="movie__images">
                            <span class="movie__rating"><?= $model->rating ?></span>
                            <?php
                            $model_array = unserialize($model->photo);
                            echo '<img src="/../upload' . $model_array[0]['avatar'] . '">';
                            ?>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6">


                        <p><?= $model->shorttext ?></p>
                        <p style="color: #fe505a">
                            <span><i class="fa fa-clock-o"></i> <?= $model->duration ?></span><br>
                            <span><i
                                    class="fa fa-users"></i> <?= $model->min . ' - ' . $model->max ?> <?= Yii::t('app', 'Gamer') ?></span><br>
                            <span><i class="fa fa-star"></i> <?= $model->rating ?></span>
                        </p>
                    </div>

                </div>


                <div class="clearfix"></div>


                <h2 class="page-heading"><?= Yii::t('app', 'photos') ?></h2>

                <div class="movie__media">
                    <div class="movie__media-switch">
                        <?php
                        if ($model->photo) {
                            $model_array = unserialize($model->photo);
                            echo '<a href="#" class="watchlist list--photo" data-filter="media-photo">' . count($model_array) . ' ' . Yii::t('app', 'photos');
                            echo '</a>';
                        }
                        if ($model->youtube) {
                            $list = explode(";", $model->youtube);
                            echo '<a href="#" class="watchlist list--video" data-filter="media-video">' . count($list) . ' ' . Yii::t('app', 'videos') . '</a>';
                        }
                        ?>
                        <!--            <a href="movie-page-full.html#" class="watchlist list--video" data-filter='media-video'>10 videos</a>-->
                    </div>

                    <div class="swiper-container">
                        <div class="swiper-wrapper">

                            <?php

                            if ($model->photo) {
                                foreach ($model_array as $ims) {
                                    echo '<div class="swiper-slide media-photo">';
                                    echo '<a href="/../upload' . $ims['normal'] . '" class="movie__media-item">';
                                    echo '<img alt="" src="/../upload' . $ims['preview'] . '">';
                                    echo '</a>';
                                    echo '</div>';
                                }
                            }

                            if ($model->youtube) {
                                $list = explode(";", $model->youtube);
                                foreach ($list as $row) {
                                    //$html.='<img src="http://img.youtube.com/vi/'.trim($row).'/mqdefault.jpg" class="img-thumbnail" style="margin-right:5px;">';
                                    echo '<div class="swiper-slide media-video swiper-slide-visible">

                    	 	<a href="https://www.youtube.com/watch?v=' . trim($row) . '" class="movie__media-item">
                    	 	<span><i class="fa fa-video-camera"></i></span>
                    			<img alt="" src="http://img.youtube.com/vi/' . trim($row) . '/mqdefault.jpg">
                    		</a>
                    	</div>';

                                }
                            }
                            ?>

                        </div>
                    </div>

                </div>

            </div>
        <?php endif; ?>
        <div class="row" style="margin-bottom:50px;">
            <div class="col-md-6">
                <h2 class="page-heading" style="margin-bottom:0px"><?= Yii::t('app', 'showtime & tickets') ?></h2>
            </div>
            <div class="adress col-md-6" style="text-align:right">
                <span class="adress__marker"><i class="fa fa-compass"></i> <?= Yii::t('app', 'Address') ?>:</span>
                <label class="adress_desc"><?php
                    //echo $address[0]['title'];
                    echo $address[0]['address'];
                    ?>
                </label>
            </div>

        </div>
        <div class="choose-container">

            <?php
            $defprice = 0;
            $defuser = 1;
            if (count($tariff) > 0) {
                $defprice = $tariff[0]['cost'];
                $defuser = $tariff[0]['users'];
                $deftotal = $tariff[0]['total'];
                ?>
                <div class="row">
                    <div class="order-step-area" style="border:0px;">
                        <div class="col-md-2">
                            <div class="order-step second--step"
                                 style="width:100%;font-weight:bold;"><?= Yii::t('app', 'Tariff') ?></div>
                        </div>
                        <ul class="col-md-8 items-wrap">
                            <?php
                            foreach ($tariff as $tkey => $titem) {
                                echo "<li class='tariff-li'>";
                                if ($titem['users'] < 5) {
                                    for ($i = 0; $i < $titem['users']; $i++) echo "<i class='fa fa-user'></i>";
                                } else {
                                    echo $titem['users'] . " <i class='fa fa-user'></i>";
                                }
                                echo " - <span class='tariff-color'>" . $titem['cost'] . "€/</span><i class='fa fa-user'></i></li>";
                            }
                            ?>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <?php
            }
            ?>

            <div class="clearfix"></div>

            <div class="time-select">
                <?php
                //echo Yii::t('app','Today is {0, date,Y-m-d H:i:s}',time());
                $n = count($dataitems);
                $i = 0;
                foreach ($dataitems as $key => $item) {
                    $i++;
                    if ($i == 1)
                        $groupClass = ' group--first';
                    elseif ($i == $n)
                        $groupClass = ' group--last';
                    else
                        $groupClass = '';


                    $full = $item['full'];

                    echo '<div class="time-select__group' . $groupClass . '">';
                    echo '<div class="col-sm-4">
                    <p class="time-select__place">' . $full . '</p>
                  </div>';
                    unset($item['full']);

                    echo '<ul class="col-sm-8 items-wrap">';

                    foreach ($item as $row) {
                        if ($row['status'] == 1)
                            echo '<li class="time-select__item disable">' . $row['time'] . '</li>';
                        else {
                            echo '<li class="time-select__item show"
                            data-time="' . $row['time'] . '"
                            data-date="' . $row['date'] . '"
                            data-sid="' . $row['section_item_id'] . '"
                            data-full="' . $full . '"
                            data-short="' . $row['short'] . '"
                            data-price="' . $deftotal . '"
                            data-currency = "€"
                            data-sale = "true"
                            >';
//                            if ($row['sale']):
//                                echo '<a class="mysale" href="#">
//                            ' . $row['time'] . '
//                                <span>' . yii::t ('app', 'SALE') . '</span>
//                            </a>';
//
//                            else:
                            echo $row['time'];
//                            endif;

                            echo '</li>';

                        }
                    }
                    echo '</ul>';
                    echo '</div>';

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
                'scrollwheel' => false,
            ]);
            //$map->containerOptions=['class'=>'mapclass'];
            $map->height = '400';
            $map->width = '100%';

            $coord = new LatLng(['lat' => $address[0]['latitude'], 'lng' => $address[0]['longitude']]);
            $marker = new Marker([
                'position' => $coord,
                'title' => $address[0]['title'],
                'icon' => '/../tacticgamepoint.png',

            ]);

            // Provide a shared InfoWindow to the marker
            $marker->attachInfoWindow(
                new InfoWindow([
                    'content' => '<b>' . $address[0]['title'] . '</b><p>' . $address[0]['address'] . '</p>'
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
        (function () {
            var dsq = document.createElement('script');
            dsq.type = 'text/javascript';
            dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments
            powered by Disqus.</a></noscript>

    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES * * */
        var disqus_shortname = 'tacticegamees';
        var disqus_identifier = '<?=$model->id?>';
        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function () {
            var s = document.createElement('script');
            s.async = true;
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

<!--jQuery Alert -->
<link rel="stylesheet" href="/athems/js/jquery.alerts/jquery.alerts.css">
<script src="/athems/js/jquery.alerts/jquery.alerts.js"></script>


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
    $(document).ready(function () {
        var _lang_cnf = "<?=Yii::t('app', 'Can you confirm this?')?>";
        var _lang_bns = "<?=Yii::t('app', 'Beginning session')?>";
        var _lang_pmt = "<?=Yii::t('app', 'For payment')?>";
        var _lang_dlg = "<?=Yii::t('app', 'Confirmation Dialog')?>";
        $.alerts.okButton = "&nbsp;<?=Yii::t('app', 'Accept')?>&nbsp;";
        $.alerts.cancelButton =  "&nbsp;<?=Yii::t('app', 'Cancel')?>&nbsp;";

        $("#search-sort").selectbox({
            onChange: function (val, inst) {

                $(inst.input[0]).children().each(function (item) {
                    $(this).removeAttr('selected');
                })

                $(inst.input[0]).find('[value="' + val + '"]').attr('selected', 'selected');
                var up = val.split('|');
                $('#quest-user').val(up[0]);
                $('#quest-price').val(up[1]);
                $('#quest-total').val(up[2]);
                $('.booking-cost').text(up[2] + '€');
                    calcProcent();
            }

        });

        function calcProcent(){
            var qtotal = $('#quest-total').val();
            var qprocent = $('#quest-procent').val();
            if(qprocent > 0) {
                var discPrice = qtotal - qtotal / 100 * qprocent;
                $('.booking-cost').html(discPrice+'€ <sup>'+qtotal+'€</sup>');
                $('#quest-price').val(discPrice);
            }else{
                $('.booking-cost').text(qtotal + '€');
            }
        }

        $('#quest-promocode-btn').click(function(){
            var _promocode = $('#quest-promocode').val();

            if(_promocode != "") {
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                var fields = {
                    code: _promocode,
                    id: $('#quest-section').val(),
                    _csrf: csrfToken
                }
                $.post("/quest/promocode", fields, function (json) {
                    var obj = $.parseJSON(json);
                    if(obj.status == 1) {
                        $('#quest-procent').val(obj.procent);
                        jAlert("<?=Yii::t('app','Your discount')?>: "+obj.procent+"%","<?=Yii::t('app','Congratulations!')?>");
                    }else{
                        jAlert("<?=Yii::t('app','Your promotional code is incorrect or has expired')?>");
                        $('#quest-procent').val(0);
                    }
                    calcProcent();
                 });
            }
        });

        <?php
        if ($dev) {
            echo "init_MoviePage();";
            echo "init_MoviePageFull();";
        }
        ?>

        $("[data-toggle='tooltip']").tooltip();
        $('.items-wrap .show').on('click', function () {
            var short = $(this).data('short');
            var time = $(this).data('time');
            var date = $(this).data('date');
            var sid = $(this).data('sid');
            var price = $(this).data('price');
            var currency = $(this).data('currency');

            $('.booking-ticket').text(short + ', ' + time);
            $('.booking-cost').text(price +currency);

            $('#quest-time').val(time);
            $('#quest-date').val(date);
            $('#quest-section').val(sid);
            $('#quest-price').val(price);


            var modalContainer = $('#my-modal');
            var modalBody = modalContainer.find('.modal-body');
            modalContainer.modal({show: true});
        });

        $("input[name=payment_type]").on("click", function () {
            if ($("input[name=payment_type]:checked").val() == 'gift') {
                $(".contact-info__field-gift").fadeIn();
            } else {
                $(".contact-info__field-gift").fadeOut();
            }
        });

        var Complete = false;
        $('#quest-button').on('click', function () {

            if (!Complete) {
                var isError = false;
                var _payment_type = $("input[name=payment_type]:checked").val();
                var _lang_type = $("input[name=language_type]:checked").val();

                if (_payment_type == 'gift') {

                    if ($("#quest-gift").val().length == 0) {
                        $("#quest-gift").addClass("input-error");
                        isError = true;
                    } else {
                        if ($('#quest-user').val() > getIntGift($("#quest-gift").val())) {
                            $("#quest-gift").addClass("input-error");
                            isError = true;
                            jAlert('The amount of your certificate is not enough to buy this tariff!', 'Alert Dialog');
                        }
                    }

                }


                $("#contact-info .request").each(function () {
                    if ($(this).val().length == 0) {
                        $(this).addClass("input-error");
                        isError = true;
                    }
                });

                if (!isError) {
                    var csrfToken = $('meta[name="csrf-token"]').attr("content");
                    var fields = {
                        client_email: $('#quest-mail').val(),
                        client_phone: $('#quest-tel').val(),
                        client_section: $('#quest-section').val(),
                        client_time: $('#quest-time').val(),
                        client_date: $('#quest-date').val(),
                        client_price: $('#quest-price').val(),
                        quest_title: $('#quest-title').val(),
                        quest_user: $('#quest-user').val(),
                        quest_gift: $('#quest-gift').val(),
                        quest_total: $('#quest-total').val(),
                        quest_pcode: $('#quest-promocode').val(),
                        quest_lang: _lang_type,
                        //Payment option
                        payment_type: _payment_type,
                        credit_type: $(".ccjs-type-read-only").html(),
                        credit_number: $("input.ccjs-number-formatted").val(),
                        credit_csc: $("input.ccjs-csc").val(),
                        credit_name: $("input.ccjs-name").val(),
                        credit_month: $(".ccjs-month option:selected").text(),
                        credit_year: $(".ccjs-year option:selected").text(),
                        id_source: 'order',
                        _csrf: csrfToken
                    };

                    var l = Ladda.create(this);

                    if (_payment_type == 'none') {
                        var cnfR = "<b>" + _lang_cnf + "</b><br>";
                        cnfR += _lang_bns + ": " + $('.booking-ticket').text();
                        cnfR += "<br>" + _lang_pmt + ": " + $('#quest-total').val()+"€";

                        jConfirm(cnfR, _lang_dlg, function (r) {
                            if (r) {
                                l.start();
                                sendRequest(fields, _payment_type,l);
                                Complete = true;
                                setInterval(function () {
                                    Complete = false;
                                }, 2000);
                            } else {
                                Complete = false;
                            }
                        });
                    } else {
                        l.start();
                        sendRequest(fields, _payment_type,l);
                        Complete = true;
                        setInterval(function () {
                            Complete = false;
                        }, 2000);
                    }
                }
            }
        });

        function isInt(value) {
            if (isNaN(value)) {
                return false;
            }
            var x = parseFloat(value);
            return (x | 0) === x;
        }

        function reverse(s){
            return s.split("").reverse().join("");
        }

        function getIntGift(str){
            var arrChrt = Array.from(str);
            arrChrt = arrChrt.reverse();
            var intGift = "";
            for (var i = 0; i < arrChrt.length; i++) {
                if(isInt(arrChrt[i])){
                    intGift += arrChrt[i];
                }else{
                    break;
                }
            }
            intGift = parseInt(reverse(intGift), 10);
            return intGift;
        }

        function sendRequest(fields, payment_type,l) {

            $.post("/quest/orders", fields, function (json) {
                var obj = $.parseJSON(json);
                 if (payment_type == 'gift') {
                    if (obj.url != "") {
                        window.location = obj.url;
                        $(".modal .close").click();
                        Complete = false;
                    } else {
                        jAlert("<?=Yii::t('app','Your certificate has expired or you entered an invalid code number!')?>");
                        $("#quest-gift").addClass("input-error");
                        Complete = false;
                        l.stop();
                    }
                } else if (payment_type != 'lacaixa' && payment_type != 'lacaixademo') {
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
                    my_tb = document.createElement('INPUT');
                    my_tb.type = 'TEXT';
                    my_tb.name = 'Ds_SignatureVersion';
                    my_tb.value = obj.ds_signatureversion;
                    my_form.appendChild(my_tb);
                    my_tb = document.createElement('INPUT');
                    my_tb.type = 'TEXT';
                    my_tb.name = 'Ds_MerchantParameters';
                    my_tb.value = obj.ds_merchantparameters;
                    my_form.appendChild(my_tb);
                    my_tb = document.createElement('INPUT');
                    my_tb.type = 'TEXT';
                    my_tb.name = 'Ds_Signature';
                    my_tb.value = obj.ds_signature;
                    my_form.appendChild(my_tb);
                    document.body.appendChild(my_form);
                    my_form.submit();
                }
            });
        }

        $('input,textarea').focus(function () {
            $(this).data('placeholder', $(this).attr('placeholder'))
            $(this).attr('placeholder', '');
            $(this).removeClass('input-error');
        });
        $('input,textarea').blur(function () {
            $(this).attr('placeholder', $(this).data('placeholder'));
        });

    });
</script>

<!-- Modal "Записаться на квест" -->
<div class="modal fade bs-example-modal-lg" id="my-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title page-heading" id="gridSystemModalLabel">
                    <?= $model->title ?>
                </h4>
            </div>
            <div class="modal-body">

                <form id="contact-info" method="post" novalidate="" class="form contact-info">

                    <ul class="book-result">
                        <li class="book-result__item"><?= Yii::t('app', 'Beginning session') ?>: <span
                                class="book-result__count booking-ticket"></span></li>
                        <!--                        <li class="book-result__item">One item price: <span class="book-result__count booking-price">$20</span></li>-->
                        <li class="book-result__item"><?= Yii::t('app', 'For payment') ?>: <span
                                class="book-result__count booking-cost"></span></li>
                    </ul>

                    <h2 class="page-heading"><?= Yii::t('app', 'Choose payment method') ?></h2>
                    <div class="payment">


                        <label class="radio-inline">
                            <input type="radio" name="payment_type" checked id="inlineRadio4"
                                   value="none"> <?= Yii::t('app', 'Payment on arrival') ?>
                        </label>

                        <label class="radio-inline">
                            <input type="radio" name="payment_type" id="inlineRadio5" value="lacaixa">
                            <img alt="" src="/../athems/images/payment/pay6.png">
                            <img alt="" src="/../athems/images/payment/pay7.png">
                        </label>

                        <label class="radio-inline">
                            <input type="radio" name="payment_type" id="inlineRadio6"
                                   value="gift"> <?=Yii::t('app', 'Gift card') ?>
                        </label>

                        <div class="contact-info__field contact-info__field-gift" style="display:none">
                            <input type="text" id="quest-gift" name="gift"
                                   placeholder="<?=Yii::t('app', 'Gift card') ?>" class="form__mail">
                        </div>
                        <div class="ccjs-card">
                            <label class="ccjs-number">
                                <?=Yii::t('app', 'Card Number') ?>
                                <input name="card-number" class="ccjs-number" placeholder="•••• •••• •••• ••••">
                            </label>

                            <label class="ccjs-csc">
                                <?= Yii::t('app', 'Security Code') ?>
                                <input name="csc" class="ccjs-csc" placeholder="•••">
                            </label>

                            <button type="button" class="ccjs-csc-help">?</button>

                            <label class="ccjs-name">
                                <?= Yii::t('app', 'Name on Card') ?>
                                <input name="name" class="ccjs-name">
                            </label>

                            <fieldset class="ccjs-expiration">
                                <legend><?= Yii::t('app', 'Expiration') ?></legend>
                                <select name="month" class="ccjs-month">
                                    <option selected disabled><?= Yii::t('app', 'MM') ?></option>
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
                                    <option selected disabled><?= Yii::t('app', 'YY') ?></option>
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
                    <h2 class="page-heading"><?= Yii::t('app', 'Number of players') ?>
                        <label class="radio-inline search">
                            <select name="sorting_item" id="search-sort" class="search__sort" tabindex="0">
                                <?php
                                $usr = '';
                                foreach ($tariff as $tkey => $titem) {
                                    $selected = '';
                                    if ($defuser == $titem['users']) $selected = " selected='selected' ";
                                    $usr .= '<option value="' . $titem['users'] . '|' . $titem['cost'] .'|' . $titem['total'] . '" ' . $selected . '>';
                                    //$usr .= $titem['users'];
                                    $usr .= $titem['users'] . " - " . $titem['cost'] . "€";
                                    $usr .= '</option>';
                                }
                                echo $usr;
                                ?>
                            </select>
                        </label></h2>


                    <h2 class="page-heading"><?= Yii::t('app', 'Contact information') ?></h2>

                    <div class="contact-info__field contact-info__field-mail">
                        <input type="text" id="quest-mail" name="user-mail"
                               placeholder="<?= Yii::t('app', 'Your email') ?>" class="form__mail request">
                    </div>
                    <div class="contact-info__field contact-info__field-tel">
                        <input type="tel" id="quest-tel" name="user-tel"
                               placeholder="<?= Yii::t('app', 'Phone number') ?>" class="form__mail request">
                    </div>
                    <div class="form" style="margin-top:15px;">
                        <label class="promolabel">
                            <h5 style="margin:10px 5px 5px 0; color:#90bf34;" ><?=Yii::t('app', 'Promotion code')?></h5></label>
                        <div style="float:left;width:150px;">
                            <input type="text" class="form__name" id="quest-promocode" value="">
                        </div>
                        <div class="btn-demo">
                            <a href="javascript:void(0)" id="quest-promocode-btn" class="btn btn-md btn--success"><i class="fa fa-check"></i></a>
                        </div>
                    </div>
                </form>


            </div>
            <div class="modal-footer progress-demo">
                <input type="hidden" id="quest-time" value="0">
                <input type="hidden" id="quest-date" value="0">
                <input type="hidden" id="quest-section" value="0">
                <input type="hidden" id="quest-price" value="<?=$defprice?>">
                <input type="hidden" id="quest-total" value="<?=$deftotal?>">
                <input type="hidden" id="quest-user" value="<?=$defuser?>">
                <input type="hidden" id="quest-title" value="<?=$model->title?>">
                <input type="hidden" id="quest-procent" value="0">
                <div class="row">
                    <div class="col-sm-8" style="text-align:left">
                        <h2 class="page-heading"
                            style="font-size:14px;margin-bottom:5px;"><?= Yii::t('app', 'LANGUAGE OF THE GAME') ?></h2>
                        <label class="radio-inline">
                            <input type="radio" name="language_type" id="inlineRadio7" checked
                                   value="esp"> <?= Yii::t('app', 'ESP') ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="language_type" id="inlineRadio8"
                                   value="eng"> <?= Yii::t('app', 'ENG') ?>
                        </label>


                    </div>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-md btn--warning btn--wide ladda-button"
                                id="quest-button" data-style="expand-right"><?= Yii::t('app', 'next') ?></button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Select -->
<script src="/athems/js/external/jquery.selectbox-0.2.min.js"></script>

<!--Credit Card-->
<script src="/athems/creditcard/creditcardjs-v0.10.12.min.js"></script>
<script>
    if (navigator.appVersion.indexOf("Chrome/") != -1) {
        // modify button
        $('a.mysale span').css("margin-left", "-14px");
    }

</script>
<style>
    .search .sbHolder {
        top: 3px !important;
        right: initial !important;
        border: 1px solid #dbdee1;
        width: 100px !important;
    }

    .search .sbHolder .sbOptions {
        width: 100px !important;
    }
    .form{
        text-align:left;
    }
    .form .btn-md{
        padding: 8px 15px 9px 17px;
    }
    .form .form__name, .form .form__mail, .form .form__message{
        border-radius:0px;
    }
    .booking-cost sup{
        color:#000;
        font-weigth:100;
        text-decoration: line-through;
    }
    .promolabel{
        float:left;
    }
    @media (max-width: 400px) {
        .contact-info .contact-info__field {
            width: 300px;
        }
        .form .form__name {
            padding: 9px 5px 10px;
        }
        .promolabel{
            float:auto;
        }
    }

</style>
