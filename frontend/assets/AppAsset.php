<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        //fonts
        //<!-- Font awesome - icon font -->
        'http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css',
        //<!-- Roboto -->
        'http://fonts.googleapis.com/css?family=Roboto:400,100,700',
        //<!-- Open Sans -->
        'http://fonts.googleapis.com/css?family=Open+Sans:800italic',
        //
        'athems/css/gozha-nav.css',
        'athems/css/external/jquery.selectbox.css',
        'athems/rs-plugin/css/settings.css',
        'athems/css/style.css',
        'athems/css/custom.css'
    ];
    public $js = [


    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
        //'yii\jui\JuiAsset'
    ];
}
