<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    //'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    //'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
//        'gallery' => [
//            'class' => 'infoweb\gallery\Module',
//        ],
    ],
    'homeUrl' => '/admin',
    'components' => [

        'db'=>[
                'class'=>'yii\db\Connection',
                'dsn' => 'mysql:host=127.0.0.1;dbname=tacticgame',
                'username' => 'root',
                'password' => 'root',
                'charset' => 'utf8',
                'tablePrefix'=>'game_'
	    ],
	'request' => [
		'baseUrl' => '/admin'
	],
	'urlManager' => [
		'enablePrettyUrl' => true,
            	'showScriptName' => false,	
	],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
