<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\base\BaseJson;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Items */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="items-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'title_es',
            'shorttext:html',
            'shorttext_es:html',// description attribute in HTML
	        [
		        'label' => Yii::t('app','Youtube'),
		        'value' => getYotube($model->youtube),
		        'format' => 'html'
	        ],
	        [
		        'label' => Yii::t('app','Active'),
		        'value' => $model->active ? Yii::t('app','Yes') : Yii::t('app','No')
	        ],
            'creatdate',
        ],
    ]) ?>

<?php

if($model->photo){
	$model_array = unserialize($model->photo);
	foreach($model_array as $ims){
		$items[] =array(
			'url' => '/../upload'.$ims['normal'],
			'src' => '/../upload'.$ims['preview'],
			'photo_id'=>$ims['photo_id'],
			'options' => array('title' => $ims['photo_id'])
		);
	}
	//$json = \yii\helpers\BaseJson::encode($items);
	//echo $json;
	echo dosamigos\gallery\Gallery::widget(['items' => $items]);
	//echo dosamigos\gallery\Carousel::widget(['items' => $items]);
}

?>
	</div>
<?php
//Url::to(['photodelete', 'id' => $model->id,'photo_id'=>$photoID])
$url=Url::to(['photodelete', 'id' => $model->id]);
$script = "
$('.gallery-item .remove-photo').on('click', function(e) {
    if (confirm('Remove this image?') == true) {
       var photo_id=$(this).data('id');
       $.ajax({
        url: '$url&photo_id='+photo_id,
           success: function(data) {
              $('.'+photo_id).remove();
           }
        });
    }
    return false;
});";

$this->registerJs($script);

function getYotube($youtube){
	$html="";
	if(isset($youtube)){
		$list = explode(";",$youtube);
		foreach($list as $row){
			$html.='<img src="http://img.youtube.com/vi/'.trim($row).'/mqdefault.jpg" class="img-thumbnail" style="margin-right:5px;">';
		}
		//
	}
	return $html;
}
