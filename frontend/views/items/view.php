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

<!-- Main content -->
<section class="container items">
<div class="col-sm-12">
	<div class="movie">
    <h2 class="page-heading"><?=$model->title?></h2>

    <div class="movie__info">
        <?php if($model->photo): ?>
        <div class="col-sm-6 col-md-6 movie-mobile">

            <div class="movie__images">
                <?php
                $model_array = unserialize($model->photo);
                 echo'<img src="/../upload'.$model_array[0]['avatar'].'">';
                ?>
            </div>


<!--            <p class="movie__option"><strong>Country: </strong><a href="movie-page-full.html#">New Zeland</a>, <a href="movie-page-full.html#">USA</a></p>-->
<!--            <p class="movie__option"><strong>Year: </strong><a href="movie-page-full.html#">2012</a></p>-->
<!--            <p class="movie__option"><strong>Category: </strong><a href="movie-page-full.html#">Adventure</a>, <a href="movie-page-full.html#">Fantazy</a></p>-->
<!--            <p class="movie__option"><strong>Release date: </strong>December 12, 2012</p>-->
<!--            <p class="movie__option"><strong>Director: </strong><a href="movie-page-full.html#">Peter Jackson</a></p>-->
<!--            <p class="movie__option"><strong>Actors: </strong><a href="movie-page-full.html#">Martin Freeman</a>, <a href="movie-page-full.html#">Ian McKellen</a>, <a href="movie-page-full.html#">Richard Armitage</a>, <a href="movie-page-full.html#">Ken Stott</a>, <a href="movie-page-full.html#">Graham McTavish</a>, <a href="movie-page-full.html#">Cate Blanchett</a>, <a href="movie-page-full.html#">Hugo Weaving</a>, <a href="movie-page-full.html#">Ian Holm</a>, <a href="movie-page-full.html#">Elijah Wood</a> <a href="movie-page-full.html#">...</a></p>-->
<!--            <p class="movie__option"><strong>Age restriction: </strong><a href="movie-page-full.html#">13</a></p>-->
<!--            <p class="movie__option"><strong>Box office: </strong><a href="movie-page-full.html#">$1 017 003 568</a></p>-->


        </div>

        <div class="col-sm-6 col-md-6">
            <p><?=$model->shorttext?></p>
        </div>
        <?php else:?>
        <div class="col-sm-12 col-md-12">
            <?=$model->shorttext?>
        </div>
        <?php endif;?>
    </div>

    <div class="clearfix"></div>

    <?php if($model->photo || $model->youtube):?>
    <h2 class="page-heading"><?=Yii::t('app','photos & videos')?></h2>
        <div class="movie__media">
        <div class="movie__media-switch">
            <?php
                if($model->photo){
                $model_array = unserialize($model->photo);
                echo'<a href="#" class="watchlist list--photo" data-filter="media-photo">'.count($model_array).' '.Yii::t('app','photos').'</a>';
                }

               if($model->youtube){
	               $list = explode(";",$model->youtube);
	               echo'<a href="#" class="watchlist list--video" data-filter="media-video">'.count($list).' '.Yii::t('app','videos').'</a>';
               }
            ?>

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
   <?php endif;?>

</div>
</div>
</section>

<div class="clearfix"></div>

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

<script type="text/javascript">
    $(document).ready(function() {
        init_MoviePage();
        init_MoviePageFull();
    });
</script>