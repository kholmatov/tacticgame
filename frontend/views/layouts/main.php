<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use yii\helpers\Url;

use backend\models\Sections;
$modelsections = Sections::find()
    ->where('active >= :active',['active'=>1])->all();
if(Yii::$app->language=='es-ES' || Yii::$app->language=='es'):
    $n =count($modelsections);
    for($i=0;$i < $n;$i++){
        $modelsections[$i]['title'] = $modelsections[$i]['title_es'];
        $modelsections[$i]['shorttext'] = $modelsections[$i]['shorttext_es'];
        $modelsections[$i]['duration'] = $modelsections[$i]['duration_es'];
    }
endif;


/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <!-- Mobile Specific Metas-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="telephone=no" name="format-detection">
    <meta name='description' content='Nuevo Escape Room en Barcelona "La casa Paranormal". Tienes 60 minutos para salir. Juego de 2 a 5 personas.' />
    <meta name='keywords' content='escape room, escape room barcelona, escapes rooms, escapes rooms barcelona, escape rooms miedo, escape room tematica de miedo, escapes rooms tematicas de miedo, barcelona escape room, barcelona escapes rooms, room escape, rooms escapes, room escape barcelona, rooms escapes barcelona, tactic escape room , tactic escapes rooms, escape room tactic, escapes rooms tactic, tacticgame escape room, escape room tacticgame, juego de escapar de habitacion, juegos de escapes de habitacion, juegos de escapar de habitaciones, habitacion de escape, escapar de habitaciones, tactic escapes de habitacion, juegos de escapar de habitaciones en barcelona tactic, tactic room escape , habitaciones para escapar encontrando enigma y llaves, habitaciones para escapar encontrando enigmas y pistas, habitaciones para escapar encontrando llaves, tactic barcelona escape room, juegos de escape rooms para escapar de habitaciones, donde ir con amigos, pasar buen tiempo en barcelona, planes divertidos con mi gente, planes divertidos para ir con amigos, un plan con mis amigos, hacer algo diferente con amigos, lugares divertidos para venir con los amigos, lugares distintos de lo normal, lugar donde puedo pasarlo bien con un amigo, sitio interesante para compartir el tiempo con mis amigos, algo diferente en barcelona ? , planes para una tarde, la casa paranormal, la casa paranormal escape room, tactic escape room la casa paranormal, casa de terror, casa de terror escape room, casa de miedo tactic barcelona, casa de miedo, casa de miedo en barcelona, miedo divertido en barcelona, el mejor escape room de Barcelona, los mejores escape rooms de Barcelona, los mejores lugares de Barcelona, lugares perfectos de Barcelona, lugares para pasar bien el tiempo, lugar perfecto para pasar tiempo con amigos, juegos divertidos para grupos de amigos, jugar con amigos, pasar tiempo, desconectar,  la casa paranormal, casa anormal, casa de miedo, casa de espiritu, casa de fantasmas, tactic game.es, tactic juegos con amigos, tactic casa de miedo, tactic game escape room, tactic juegos divertidos, tactic game es, tactic casa paranormal, tactic casa anormal, tactic escape room de miedo, escape room in Barcelona, escape room в Барселоне, квесты в Барселоне, русские квесты в Барселоне, тактик барселона, тактик, tactic, time cafe en Barcelona, time cafe, tactic time cafe, tactic time cafe en barcelona, tactic juegos de mesa, juegos de mesa, juegos de mesa en barcelona, jugar con amigos en barcelona, sitio ideal para pasar bien el tiempo, time cafe juegos de mesa, time cafe ceremonias de te, ceremonias de te en barcelona, mirar peliculas en grupos, jugar a mafia, jugar a mafia en barcelona, juegos de roll en barcelona, juegos de roll, juegos divertidos, mirar cine, noche de cine en barcelona, noches de cine, noches de cine in barcelona, cinemaniaticos, cinemaniaticos en barcelona, anti cafe en barcelona, антикафе в барселоне, alquiler de espacio en barcelona, alquiler de espacios, alquilo espacio para cumpleaños, team building en Barcelona, formacion de grupos, formacion de equipos, formacion de grupos en barcelona, formacion de equipos en barcelona, formaciones de grupos de trabajo, formaciones de equipos de trabajo, chernobyl, chernobil en barcelona, chernobil, chernobyl en barcelona, escape room chernobil, escape room chernobil en barcelona, escape room chernobyl, escape room chernobyl en barcelona, juego de chernobil, juego de chernobyl, juego de escape chernobil, juego de escape chernobyl, juego de escape en barcelona, escapada a chernobil,escapada a chernobyl, jugar a escape en bacelona, juegos en barcelona, jugar en barcelona, juegos para amigos, juegos para grupos de amigos, juegos divertidos en barcelona, juegos para amigos en barcelona, escape a chernobil, escapada a chernobil en barcelona, escape room la casa paranormal, escape room chernobil, reservar escape room, reservar room escape, reservar escape room en barcelona, reservar room escape en barcelona' />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- Modernizr -->
    <script src="/athems/js/external/modernizr.custom.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.3.0/respond.js"></script>
    <![endif]-->


    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-69542045-1', 'auto');
      ga('send', 'pageview');

    </script>


    <!--
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-69542045-1', 'auto', 'Site Author');
        ga('create', 'UA-49293133-21', 'auto', 'Luz Roja');
        ga('send', 'pageview');

    </script>
    -->

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter33439778 = new Ya.Metrika({
                        id:33439778,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true,
                        webvisor:true,
                        trackHash:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/33439778" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    <script type="text/javascript">
    <?php
     echo "var errorname ='".Yii::t('app','Error! Write your name!')."';";
     echo "var erroremail ='".Yii::t('app','Error! Wrong email!')."';";
     echo "var errorphone ='".Yii::t('app','Error! Write your phone!')."';";
     echo "var errormessage ='".Yii::t('app','Error! Write message!')."';";
     echo "var ithank ='".Yii::t('app','Thank you!')."';";
     echo "var iyour ='".Yii::t('app','your message successfully sent')."';";
    ?>
    </script>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrapper">

    <!-- Header section -->
    <header class="header-wrapper header-wrapper--home">
        <div class="container">
            <!-- Logo link-->
            <a href='<?=Url::toRoute(['/site/index'])?>' class="logo">
                <img alt='logo' src="/images/logo.png">
            </a>

    <nav id="navigation-box">
        <?php $urlCrnt = Url::current();?>
        <!-- Toggle for mobile menu mode -->
        <a href="<?=$urlCrnt?>#" id="navigation-toggle">
            <span class="menu-icon">
                <span class="icon-toggle" role="button" aria-label="Toggle Navigation">
                    <span class="lines"></span>
                </span>
            </span>
        </a>
        <?php
        $active = ' class="active"';
        $cm1=$cm2=$cm3=$cm4=$cm5=$cm6="";
        SWITCH($urlCrnt){
            CASE Url::toRoute(['/site/index']):
                $cm1 = $active;
                break;
            CASE Url::toRoute(['/gallery/index']):
                $cm2 = $active;
                break;
            CASE Url::toRoute(['/certification/view', 'id' => 1]):
                $cm3 = $active;
                break;
            CASE Url::toRoute(['/items/view','id'=>3]):
                $cm4 = $active;
                break;
            CASE Url::toRoute(['/site/contact']):
                $cm5 = $active;
                break;
//            DEFAULT:
//                $cm6 = $active;
//                break;
        }
        ?>
        <!-- Link navigation -->
        <ul id="navigation" >
            <li<?=$cm1?>>
                <a href="<?=Url::toRoute(['/site/index'])?>"><?=Yii::t('app','Main')?></a>
            </li>
            <li <?=$cm6?>>
                <span class="sub-nav-toggle plus"></span>
                <a href="javascript::void(0)"><?=Yii::t('app','Games')?></a>
                <ul>
                    <?php
                    foreach($modelsections as $item) {
                        echo '<li class="menu__nav-item"><a href="' . Url::toRoute(['/quest/view', 'id' => $item->id]) . '">' . $item->title . '</a></li>';
                    }
                    ?>
                </ul>
            </li>
            <li<?=$cm2?>>
                <a href="<?=Url::toRoute(['/gallery/index'])?>"><?=Yii::t('app','Gallery')?></a>
            </li>
            <li<?=$cm3?>>
                <a href="<?=Url::toRoute(['/certification/view', 'id' => 1])?>"><?=Yii::t('app','Bonus gift')?></a>
            </li>
            <li<?=$cm4?>>
                <a href="<?=Url::toRoute(['/items/view','id'=>3])?>"><?=Yii::t('app','FAQ')?></a>
            </li>
            <li<?=$cm5?>>
                <a href="<?=Url::toRoute(['/site/contact'])?>"><?=Yii::t('app','Contact')?></a>
            </li>
            <li>
                <?=frontend\widgets\wLang::widget();?>
            </li>
        </ul>
    </nav>

    </div>
    </header>

    <div class="wrap">

    <?= $content ?>
    <!-- Custom -->

    <footer class="footer-wrapper">
        <section class="container">
            <div class="col-xs-12 col-md-4 footer-nav">
<!--                <ul class="nav-link">-->
<!--                    <li><a href="#">--><?//=Yii::t('app','Main')?><!--</a></li>-->
<!--                    <li>/</li>-->
<!--                    <li><a href="#">--><?//=Yii::t('app','Rooms')?><!--</a></li>-->
<!--                    <li>/</li>-->
<!--                    <li><a href="#">--><?//=Yii::t('app','Booking')?><!--</a></li>-->
<!--                    <li>/</li>-->
<!--                    <li><a href="#">--><?//=Yii::t('app','Reserve')?><!--</a></li>-->
<!--                    <li>/</li>-->
<!--                    <li><a href="#">--><?//=Yii::t('app','Time cafe')?><!--</a></li>-->
<!--                    <li>/</li>-->
<!--                    <li><a href="#">--><?//=Yii::t('app','About us')?><!--</a></li>-->
<!--                    <li>/</li>-->
<!--                    <li><a href="#">--><?//=Yii::t('app','Contacs')?><!--</a></li>-->
<!--                </ul>-->
                <img src="http://tacticgame.es/images/logo.png" class="img-responsive" style="margin-top:40px" />
                <p class="copy" style="margin:10px 0 0 20px">
                    Carrer de los Castillejos 287,bj<br>
                    08025 Barcelona<br>
                    Tel. +34 934 63 52 21<br>
                <a href="mailto:info@tacticgame.es" style="color:#fff">info@tacticgame.es</a></p>
            </div>

            <div class="col-xs-12 col-md-4">
                <div class="footer-info">
                    <div class="social">
                        <a href='https://www.facebook.com/tacticgame.es/' target="_blank" class="social__variant fa fa-facebook"></a>
<!--                        <a href='http://twitter.com/' class="social__variant fa fa-twitter"></a>-->
                        <a href='http://vk.com/club102014074' target="_blank" class="social__variant fa fa-vk"></a>
                        <a href='https://instagram.com/tacticescaperoom.es/' target="_blank" class="social__variant fa fa-instagram"></a>
<!--                        <a href='http://tumblr.com/' class="social__variant fa fa-tumblr"></a>-->
<!--                        <a href='http://pinterest.com/' class="social__variant fa fa-pinterest"></a>-->
                        <?=frontend\widgets\wLang::widget();?>
                    </div>

                    <div class="clearfix"></div>
                    <p class="copy" style="margin: 20px 0 0 12px">&copy; Tactic Games LTD, <?=date('Y')?>. <?=Yii::t('app','All rights reserved')?>.</p>
                </div>
            </div>

            <div class="col-xs-12 col-md-4">
                <div id="TA_selfserveprop134" class="TA_selfserveprop" style="margin:10px 0 10px 0">
                    <ul id="etZncHRqH" class="TA_links pvz5xZm">
                        <li id="TC14aBWe1ok" class="wo62kD">
                            <a target="_blank" href="https://www.tripadvisor.es/"><img src="https://www.tripadvisor.es/img/cdsi/img2/branding/150_logo-11900-2.png" alt="TripAdvisor"/></a>
                        </li>
                    </ul>
                </div>
                <script src="https://www.jscache.com/wejs?wtype=selfserveprop&amp;uniq=134&amp;locationId=8790688&amp;lang=es&amp;rating=true&amp;nreviews=5&amp;writereviewlink=true&amp;popIdx=true&amp;iswide=false&amp;border=true&amp;display_version=2"></script>
            </div>

        </section>
    </footer>
    </div> <!--    /wrapper-->
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
