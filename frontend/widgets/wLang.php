<?php
/**
 * Created by PhpStorm.
 * User: mistersecret
 * Date: 01/08/15
 * Time: 20:11
 */

namespace frontend\widgets;
use backend\models\Lang;

class wLang extends \yii\bootstrap\Widget
{
    public function init(){}

    public function run() {
        return $this->render('wlang/view', [
            'current' => Lang::getCurrent(),
            'langs' => Lang::find()->all(),
        ]);
    }
}