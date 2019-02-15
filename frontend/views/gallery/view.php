<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title=Yii::t('app','Gallery').' -> '.$myalbum['albtitle'];
?>
<section class="container view-photos">
    <div class="col-sm-12">
        <h2 class="page-heading">
            <span onclick="location.href='<?=Url::toRoute(['/gallery/index'])?>';" style="cursor: pointer"><?=Yii::t('app','Gallery')?></span>
            <small><i class="fa fa-folder-open"></i> <?=$myalbum['albtitle']?></small>
        </h2>
        <div class="row" style="padding: 0px 10px 45px 10px">
            <div class="col-sm-12" style="border-bottom: 1px solid #dbdee1;padding-bottom: 0px">
                <div class="myescape">
                    <span class="datepicker__marker" style="font-weight:600;margin-right:10px"><i class="fa fa-dot-circle-o"></i> <?=Yii::t('app','Escape room')?></span>
                    <span class="mega-select__filter"><?=$myalbum['title']?></span>
                </div>
                <div class="mydate">
                    <span class="datepicker__marker" style="font-weight:600;margin-right:10px"><i class="fa fa-calendar"></i> <?=Yii::t('app','Date')?></span>
                    <span class="mega-select__filter"><?=Yii::$app->formatter->asDate($myalbum['created'],'short')?></span>
                </div>

                <div style="float: right;width: 10px;font-size: 25px;cursor: pointer" onclick="history.go(-1); return false;">
                  <i class="fa fa-mail-reply"></i>
                </div>

                <div style="clear: both"></div>
            </div>

        </div>
        <div class="row">
            <div class="gallery-wrapper">
                <?php
                foreach($galleryalbum as $album):
                   $imgurl=Yii::$app->params['urlUploadGalleryPath'].'/'.$album['id'].'/'.$album['imgid'];
                 ?>
                    <div class="col-sm-4 col-md-3">
                    <div class="gallery-item">
                        <a href="<?=$imgurl.'/medium.jpg'?>" class="gallery-item__image gallery-item--photo">
                                <img class="myimg" alt='<?=$album['name']?>' src="<?=$imgurl.'/small.jpg'?>">
                        </a>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <?php
                endforeach;
                ?>
            </div>
        </div>
    <?= \yii\widgets\LinkPager::widget([
        'pagination' => $pages,
        'options'=>['class'=>'pagination paginatioon--full'],
        'prevPageCssClass'=>'pagination__prev',
        'prevPageLabel'=>Yii::t('app','Prev'),
        'nextPageCssClass'=>'pagination__next',
        'nextPageLabel'=>Yii::t('app','Next')
    ]);
    ?>

</div>

</section>
<div class="clearfix"></div>
<!-- Magnific-popup -->
<link href="/athems/css/external/magnific-popup.css" rel="stylesheet" />
<!-- jQuery 1.9.1-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/athems/js/external/jquery-1.10.1.min.js"><\/script>')</script>
<!-- Mobile menu -->
<script src="/athems/js/jquery.mobile.menu.js"></script>

<!-- Magnific-popup -->
<script src="/athems/js/external/jquery.magnific-popup.min.js"></script>
<script type="text/javascript">

    $('#navigation').mobileMenu({
        triggerMenu:'#navigation-toggle',
        subMenuTrigger: ".sub-nav-toggle",
        animationSpeed:500
    });

    function init_Gallery () {
        "use strict";
        //1. Pop up fuction for gallery elements

        //pop up for photo (object - images)
        $('.gallery-item--photo').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            mainClass: 'mfp-fade',
            image: {
                verticalFit: true
            },
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0,1] // Will preload 0 - before current, and 1 after the current image
            }

        });

        //pop up for photo (object - title link)
        $('.gallery-item--photo-link').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            mainClass: 'mfp-fade',
            image: {
                verticalFit: true
            },
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0,1] // Will preload 0 - before current, and 1 after the current image
            }

        });

        //pop up for video (object - images)
        $('.gallery-item--video').magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,

            fixedContentPos: false,
            gallery: {
                enabled: true,
                preload: [0,1] // Will preload 0 - before current, and 1 after the current image
            }
        });

        //pop up for video (object - title link)
        $('.gallery-item--video-link').magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,

            fixedContentPos: false,
            gallery: {
                enabled: true,
                preload: [0,1] // Will preload 0 - before current, and 1 after the current image
            }
        });
    }
    $(document).ready(function() {
        init_Gallery();
    });
</script>