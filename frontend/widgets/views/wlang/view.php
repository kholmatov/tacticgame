<?php
/**
 * Created by PhpStorm.
 * User: mistersecret
 * Date: 01/08/15
 * Time: 20:13
 */
use yii\helpers\Html;
?>
<?php foreach ($langs as $lang):
    $img = "<img src='/images/lang/$lang->url.png' style='height:22px'>";
    if($current->name==$lang->name): ?>
        <span id="current-lang" class="mybtn" style="color: #f25549;">
           <?=$img?>
        </span>
     <?php else:
            //$lang->name
            echo Html::a($img, '/'.$lang->url.Yii::$app->getRequest()->getLangUrl(),['class' => 'mybtn']);
        endif;
    endforeach;
?>

