<?php

//Maps
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\layers\BicyclingLayer;
use dosamigos\google\maps\overlays\InfoWindow;
//use dosamigos\google\maps\services\GeocodingClient;
use yii\helpers\Url;
use common\components\LangUrlManager;
/* @var $this yii\web\View */
$this->title = Yii::t('app','Tactic Escape Room Barcelona');
?>
<style>
    .header-wrapper--home {
        position: relative;
    }
</style>

<!-- Slider -->
<div class="bannercontainer">
<div class="banner">
<ul>
<?php

foreach($modelsections as $item){
    echo'<li data-transition="fade" data-slotamount="7" class="slide" data-slide="'.$item->title.'">';
    echo'<img alt="" src="/../upload'.$item->slide.'">';
	?>
	<div class="caption slide__name slide__name--smaller"
	     data-x="left"
	     data-y="160"

	     data-splitin="chars"
	     data-elementdelay="0.1"

	     data-speed="700"
	     data-start="1400"
	     data-easing="easeOutBack"

	     data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:0;transformOrigin:50% 50%;"

	     data-frames="{ typ :lines;
                                                 elementdelay :0.1;
                                                 start:1650;
                                                 speed:500;
                                                 ease:Power3.easeOut;
                                                 animation:x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:1;transformPerspective:600;transformOrigin:50% 50%;
                                                 },
                                                 { typ :lines;
                                                 elementdelay :0.1;
                                                 start:2150;
                                                 speed:500;
                                                 ease:Power3.easeOut;
                                                 animation:x:0;y:0;z:0;rotationX:00;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:1;transformPerspective:600;transformOrigin:50% 50%;
                                                 }
                                                 "


	     data-splitout="lines"
	     data-endelementdelay="0.1"
	     data-customout="x:-230;y:0;z:0;rotationX:0;rotationY:0;rotationZ:90;scaleX:0.2;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%"
	     data-endspeed="500"

	     data-endeasing="Back.easeIn"
		>
		<?=$item->title?>
	</div>

	<div class="caption slide__time position-center postion-place--one sfr stl"
	     data-x="left"
	     data-y="242"
	     data-speed="300"
	     data-start="2100"
	     data-easing="easeOutBack"
	     data-endspeed="300"
		data-endeasing="Back.easeIn">
			<i class="fa fa-clock-o"></i> <?=$item->duration?>
	</div>
	<div class="caption slide__date position-center postion-place--four lfb ltb"
	     data-x="left"
	     data-y="242"
	     data-speed="500"
	     data-start="2800"
	     data-easing="Power4.easeOut"
	     data-endspeed="400"

	     data-endeasing="Back.easeIn">
		<i class="fa fa-users"></i> <?=$item->min.' - '.$item->max?> <?=Yii::t('app','Gamers')?>
	</div>

	<div class="caption lfb slider-wrap-btn ltb"
	     data-x="left"
	     data-y="310"
	     data-speed="400"
	     data-start="3300"
	     data-easing="Power4.easeOut"
	     data-endspeed="500"
	     data-endeasing="Power4.easeOut" >
		<a href="<?=Url::toRoute(['/quest/view', 'id' => $item->id])?>"
           onclick="window.location.href='<?=Url::toRoute(['/quest/view', 'id' => $item->id])?>';"
           class="btn btn-md btn--danger btn--wide slider--btn specbtn"
           data-url="<?=Url::toRoute(['/quest/view', 'id' => $item->id])?>">
            <?=Yii::t('app','Book a ticket')?>
        </a>
	</div>
	<?php
    echo'</li>';
}
?>
</ul>
</div>
</div>
<!--end slider -->

<!-- Main content -->
<section class="container">

    <div class="movie-best">
    <div class="col-sm-10 col-sm-offset-1 movie-best__rating"><?=Yii::t('app','ESCAPE ROOMS')?></div>
    <div class="col-sm-12 change--col genrial">

        <?php
        $cc = 0;
        foreach($modelsections as $item){
            $cc++;
            echo '<div class="movie-beta__item movie">';
            $model_array = unserialize($item->photo);
            echo'
                <a href="'.Url::toRoute(['/quest/view', 'id' => $item->id]).'">
                <div class="movie__images">
                    <img src="/../upload'.$model_array[0]['avatar'].'">
                    <span class="best-rate">'.$item->title.'</span>
                </div>
                </a>
                ';

             echo'
				      <div class="movie__info">
  							<!--<div class="movie__rate">
                                   <img  src="/athems/images/icons/stars.svg">
                                   <span class="movie__rating">'.$item->rating.'</span>
                              </div>

                              <div class="movie__option">
  								<a href="'.Url::toRoute(['/quest/view', 'id' => $item->id]).'">'.$item->title.'</a>
  							</div>-->
  							<div class="movie__user">'.$item->min.' - '.$item->max.' '.Yii::t('app','Gamers').'</div>
  							<div class="movie__time">'.$item->duration.'</div>';
            if($cc != 4)
            {
                  echo '<div class="movie__button">
                      <a class="btn btn-md btn--danger btn--wide slider--btn specbtn" href="'.Url::toRoute(['/quest/view', 'id' => $item->id]).'">Reserva ya</a>
                  </div>';
            }

            echo '
  		          <div style="clear:both"></div>
              </div>';

            echo'</div>';
         }

        ?>

        <div class="movie-beta__item movie">
            <a href="<?=Url::toRoute(['/certification/view', 'id' => 1])?>">
                <div class="movie__images">
                    <img src="/../upload/certification/cupon%20de%20regalo3.jpg">
                    <span class="best-rate"><?=Yii::t('app','Bonus gift')?></span>
                </div>
            </a>


            <div class="movie__info">
                <div class="movie__user">2 - 5 <?=Yii::t('app','Gamers')?></div>
                <div class="movie__time">60</div>

                <div class="movie__button">
                    <a class="btn btn-md btn--danger btn--wide slider--btn specbtn" href="<?=Url::toRoute(['/certification/view', 'id' => 1])?>"><?=Yii::t('app','BUY')?></a>
                </div>


                <div style="clear:both"></div>
            </div>
        </div>


    </div>
    <div class="col-sm-10 col-sm-offset-1 movie-best__check"></div>
</div>
    <div class="col-sm-12">
        <h2 class="title-pages">
            <label><?=Yii::t('app','VIDEO')?></label>
        </h2>
        <div class="cafe">
            <div class="clearfix"></div>
            <div class="col-sm-12">

                <?php
                $video_case = '227238510';
                $video_cher = '228001931';
                $video_epid = '228004078';
                if(Yii::$app->language == 'en'){
                    $video_case = '227232498';
                    $video_cher = '228003107';
                    $video_epid = '228373418';
                }
                ?>

                <div class="gallery-wrapper">
                    <div class="col-sm-4">
                        <div class="gallery-item" style="text-align: left;">
                            <div>
                                <iframe src="https://player.vimeo.com/video/<?=$video_case?>" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            </div>
                            <a href="<?=Url::toRoute(['/quest/view', 'id' => 1])?>" class="gallery-item__descript gallery-item--video-link">
                                <span class="gallery-item__icon"><i class="fa fa-video-camera"></i></span>
                                <p class="gallery-item__name">LA CASA PARANORMAL</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="gallery-item" style="text-align: left;">
                            <div>
                                <iframe src="https://player.vimeo.com/video/<?=$video_cher?>" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            </div>
                            <a href="<?=Url::toRoute(['/quest/view', 'id' => 2])?>" class="gallery-item__descript gallery-item--video-link">
                                <span class="gallery-item__icon"><i class="fa fa-video-camera"></i></span>
                                <p class="gallery-item__name">CHERNÓBIL</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="gallery-item" style="text-align: left;">
                            <div>
                                <iframe src="https://player.vimeo.com/video/<?=$video_epid?>" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            </div>
                            <a href="<?=Url::toRoute(['/quest/view', 'id' => 3])?>" class="gallery-item__descript gallery-item--video-link">
                                <span class="gallery-item__icon"><i class="fa fa-video-camera"></i></span>
                                <p class="gallery-item__name">EPIDEMIA Z</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12"  style="margin-top:-110px">
        <h2 class="title-pages map-title">
            <label><?=Yii::t('app','OUR LOCATION')?></label>
        </h2>
    </div>
</section>
<div class="clearfix"></div>

<div class="maps-position">
<?php
if(isset($modelposition)){
    $coord = new LatLng(['lat' => '41.4074043000', 'lng' => ' 2.1769690000']);
    $map = new Map([
        'center' => $coord,
        'zoom' => 15,
        'scrollwheel'=> false,
    ]);

    $map->height='350';
    $map->width='100%';

    foreach($modelposition as $item){
       $coord = new LatLng(['lat' => $item->latitude, 'lng' => $item->longitude]);
       $marker = new Marker([
            'position' => $coord,
            'title' => $item->title,
            'icon'=>'/../tacticgamepoint.png',

        ]);

        // Provide a shared InfoWindow to the marker
        $marker->attachInfoWindow(
            new InfoWindow([
                'content' => '<b>'.$item->title.'</b><p>'.$item->address.'</p>'
            ])
        );

        // Add marker to the map
        $map->addOverlay($marker);
    }

        //========================================================
        // Lets show the BicyclingLayer :)
        $bikeLayer = new BicyclingLayer(['map' => $map->getName()]);

        // Append its resulting script
        $map->appendScript($bikeLayer->getJs());

        // Display the map -finally :)
        echo $map->display();

}
?>
</div>
<div class="clearfix"></div>
<!-- JavaScript-->
<!-- jQuery 1.9.1-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/athems/js/external/jquery-1.10.1.min.js"><\/script>')</script>
<!-- Migrate -->
<script src="/athems/js/external/jquery-migrate-1.2.1.min.js"></script>
<!-- Bootstrap 3-->
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

<!-- jQuery REVOLUTION Slider -->
<script type="text/javascript" src="/athems/rs-plugin/js/jquery.themepunch.plugins.min.js"></script>
<script type="text/javascript" src="/athems/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>

<!-- Mobile menu -->
<script src="/athems/js/jquery.mobile.menu.js"></script>
<!-- Select -->
<script src="/athems/js/external/jquery.selectbox-0.2.min.js"></script>
<!-- Stars rate -->
<script src="/athems/js/external/jquery.raty.js"></script>

<!-- Form element -->
<script src="/athems/js/external/form-element.js"></script>
<!-- Form validation -->
<script src="/athems/js/form.js"></script>

<!-- Twitter feed -->
<!--<script src="/athems/js/external/twitterfeed.js"></script>-->

<!-- Custom -->
<script src="/athems/js/custom.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        init_Home();
    });
</script>
