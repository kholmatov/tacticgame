<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'homeUrl' => '/',
	'language' => 'es',
    //'sourceLanguage' => 'en',
	'components' => [
	    'i18n' => [
		    'translations' => [
			    '*' => [
				    'class' => 'yii\i18n\PhpMessageSource',
				    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'en',
				    'fileMap' => [
					    //'app' => 'app.php',
					    //'app/auth' => 'auth.php'
				    ],
			    ],
		    ],
	    ],
//        'mailer' => [
//            'class' => 'yii\swiftmailer\Mailer',
////            'transport' => [
////                'class' => 'Swift_SmtpTransport',
////                'host' => 'smtp.yandex.ru',
////                'username' => 'info@tacticgame.es',
////                'password' => 'Alan870629',
////                'port' => '465',
////                'encryption' => 'SSL',
////             ],
//          ],
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',//'smtp.yandex.ru',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
                'username' => 'info@tacticgame.es',
                'password' => 'Alan870629',
                'port' => '465',
                'encryption' => 'ssl',
                'plugins' => [
                            [
                                'class' => 'Swift_Plugins_ThrottlerPlugin',
                                'constructArgs' => [20],
                            ],
                    ],
                ]
            ],
        'formatter' => [
            'timeZone' => 'Europe/Madrid',
            'dateFormat' => 'd.MM.Y',
            'timeFormat' => 'H:mm:ss',
            'datetimeFormat' => 'd.MM.Y H:mm',
        ],
	'request'=>[
		'baseUrl'=> '',
        'enableCookieValidation' => false,
		'class' => 'common\components\LangRequest'
	],
	'urlManager' => [
		'enablePrettyUrl' => true,
		'showScriptName' => false,
        'class'=>'common\components\LangUrlManager',
        'rules'=>
            [
                '/' => 'site/index',
                '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
                '<_c:[\w\-]+>' => '<_c>/index',
                '<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_c>/<_a>',
                '<_c:[\w\-]+>/<id:\d+>/dev' => '<_c>/view',
            ]
//            [
//            '/' => 'site/index',
//            '<controller:\w+>/<action:\w+>/'=>'<controller>/<action>',
//        ]
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
