<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
use backend\models\Sections;

$this->title=Yii::t('app','Gallery');
?>
<section class="container kh-gallery" >
    <div class="col-sm-12">
        <h2 class="page-heading"><?=Yii::t('app','Gallery')?></h2>

        <div class="row" style="padding: 0px 10px 45px 10px;">
            <div class="col-sm-12" style="border-bottom: 1px solid #dbdee1;padding-bottom: 0px">

                <div id="select" class="myselect">
                    <span  class="datepicker__marker" style="font-weight:600;">
                        <i class="fa fa-dot-circle-o"></i> <?=Yii::t('app','Choose escape room')?>
                    </span>
                    <span class="mega-select__filter">
                        <form class="select" method='get'>
                            <select name="select_item"  class="select__sort" tabindex="0">
                             <?php
                                echo '<option value="0">'.Yii::t('app','All').'</option>';
                                foreach($mysection as $row){
                                    if($section==$row['id']) $selected=" selected"; else $selected="";
                                    echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['stitle'].'</option>';
                                 }
                            ?>
                         </select>
                     </form>
                    </span>
                </div>
                <div class="datepicker">
                    <span class="datepicker__marker" style="font-weight:600;margin-right:10px"><i class="fa fa-calendar"></i> <?=Yii::t('app','Date')?></span>
                    <span class="mega-select__filter"></span>
                    <input type="text" id="datepicker" value='<?=$date?>' class="datepicker__input">
                </div>
                <?php if($show):?>
                <div onclick="unsetfilter()"  style="display: inline-block;position: relative;color:#f00;width: 10px;font-size: 20px;cursor: pointer;margin:-10px 0 0 20px;">
                    <i class="fa fa-times-circle-o"></i>
                </div>
                <?php endif;?>
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
                        <a href="<?=Url::toRoute(['/gallery/view', 'id' => $album['id']])?>" class="gallery-item__image gallery-item--photo">
                                <img class="myimg" alt='<?=$album['title']?>' src="<?=$imgurl.'/small.jpg'?>">
                        </a>
                        <a href='images/gallery/large/item-1.jpg' class="gallery-item__descript gallery-item--photo-link">
                            <span class="album-name">
                                <?=$album['title']?>
                            </span>
                            <span class="album-date">
                                <i class="fa fa-calendar"></i> <?=Yii::$app->formatter->asDate($album['created'],'short')?>
                            </span>
                            <p class="gallery-item__name"><i class="fa fa-tag"></i> <?php
                                if(strlen($album['stitle']) > 15)
                                   $sname= substr($album['stitle'],0,15).'...';
                                else
                                    $sname= $album['stitle'];
                                echo mb_strtolower($sname, 'UTF-8');
                                ?></p>
                        </a>
                        <div style="xclear: both"></div>
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
<?php
$this->registerCssFile("http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css");
?>
<!-- jQuery 1.9.1-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/athems/js/external/jquery-1.10.1.min.js"><\/script>')</script>
<!-- Migrate -->
<script src="/athems/js/external/jquery-migrate-1.2.1.min.js"></script>
<!-- jQuery UI -->
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<!-- Bootstrap 3-->
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

<!-- Select -->
<script src="/athems/js/external/jquery.selectbox-0.2.min.js"></script>

<!-- Mobile menu -->
<script src="/athems/js/jquery.mobile.menu.js"></script>
<?php if($mycheck) $mydate = '"'.$date.'"'; else $mydate='""';?>
<script type="text/javascript">

    $('#navigation').mobileMenu({
        triggerMenu:'#navigation-toggle',
        subMenuTrigger: ".sub-nav-toggle",
        animationSpeed:500
    });
    function unsetfilter(){
        window.location.href ='?';
    }

    function init_MovieList () {
        "use strict";

        var section = <?=$section?>;
        var date = <?=$mydate?>;

        //1. Dropdown init
        //select
        $(".select__sort").selectbox({
            onChange: function (val, inst) {
                section=val;
                $(inst.input[0]).children().each(function(item){
                    $(this).removeAttr('selected');
                })
                $(inst.input[0]).find('[value="'+val+'"]').attr('selected','selected');
                filter();
            }

        });


        //2. Datepicker init
        $( ".datepicker__input" ).datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            showAnim:"fade",
            onSelect: function(dateText) {
                date = this.value;
                filter();
            }
        });

        $(document).click(function(e) {
            var ele = $(e.target);
            if (!ele.hasClass("datepicker__input") && !ele.hasClass("ui-datepicker") && !ele.hasClass("ui-icon") && !$(ele).parent().parents(".ui-datepicker").length){
                $(".datepicker__input").datepicker("hide");
            }
        });

        function filter(){
            window.location.href = '?section='+section+'&date='+date+'&show=1';
        }

    }


    $(document).ready(function() {
        init_MovieList();
    });
</script>