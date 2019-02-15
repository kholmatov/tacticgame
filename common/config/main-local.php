<?php
use kartik\mpdf\Pdf;
return [
    'components' => [
        // setup Krajee Pdf component
        'pdf' => [
            'class' => Pdf::classname(),
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            //'charset'=>Pdf::MODE_UTF8,
            // refer settings section for all configuration options
        ],
	    'cm' => [ // bad abbreviation of "CashMoney"; not sustainable long-term
		    'class' => 'common\components\CashMoney', // note: this has to correspond with the newly created folder, else you'd get a ReflectionError

		    // Next up, we set the public parameters of the class
		    'client_id' => 'tacticgame.es-facilitator_api1.gmail.com',
		    'client_secret' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31A5QhQXTLVlgAAdVHkSdIxBgEoMKv',
		    // You may choose to include other configuration options from PayPal
		    // as they have specified in the documentation
	    ],
	    'paypal'=> [
		    'class'        => 'common\components\PayPal',
		    'clientId'     => 'AVBar8zAox3b8HQzQU82DPG524jVvWKhaq04iczwoHx53Mt0atuaM9eAeRUpcLfVPkZQbbMbuXDTTnkY',
		    'clientSecret' => 'EHOFOY3mZMmxqKeq4gLWsxdlG11GlFgJkcVJgP0lNTAs-1RCNnlmx20DpISvj1h6D5k5k1Su2pr9IUui',
		    'isProduction' => false,
		    // This is config file for the PayPal system
		    'config'       => [
			    'http.ConnectionTimeOut' => 60,
			    'http.Retry'             => 1,
			    'mode'                   => \common\components\PayPal::MODE_SANDBOX,
			    'log.LogEnabled'         => YII_DEBUG ? 1 : 0,
			    'log.FileName'           => '/usr/share/nginx/html/tacticgame.es/frontend/runtime/logs/paypal.log',
			    'log.LogLevel'           => \common\components\PayPal::LOG_LEVEL_INFO,
		    ]
	    ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=tacticgame',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'tablePrefix'=>'game_'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'ReadHttpHeader' => [
            'class' => 'common\components\ReadHttpHeader'
        ],
        'EventHelper' => [
            'class' => 'common\components\EventHelper'
        ],
//        'gii' => [
//            'class' => 'yii\gii\Module',
//            'allowedIPs' => ['127.0.0.1', '::1','89.169.15.247'] // adjust this to your needs
//
//        ],
    ]
];
