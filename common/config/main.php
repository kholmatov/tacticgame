<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'modules'=> [

//		// Module Articles
//		'articles' => [
//			'class' => 'cinghie\articles\Articles',
//			// Select Languages allowed
//			'languages' => array_merge([ "en-GB" => "en-GB" ],[ "es-ES" => "es-ES" ]),
//			// Select Editor: no-editor, ckeditor, tinymce, markdown
//			'editor' => 'ckeditor',
//
//			// Select Image Types allowed
//			//'categoryimagetype' => 'jpg,jpeg,gif,png',
//
//			// Select Image Name: original, categoryname, casual
//			//'categoryimgname' => 'categoryname',
//
//			// Select Path To Upload Category Image
//			//'categoryimagepath' => '/usr/share/nginx/www/advanced/frontend/web/upload/articles/categories/',
//			// Select Path To Upload Category Thumb
//			//'categorythumbpath' => '/usr/share/nginx/www/advanced/frontend/web/upload/articles/categories/thumb/',
//		],
//
		// Module Kartik-v Grid
		'gridview' =>  [
			'class' => '\kartik\grid\Module',

			// array the the internalization configuration for this module
			'i18n' => [
				'class' => 'yii\i18n\PhpMessageSource',
				'basePath' => '@kvgrid/messages',
				'forceTranslation' => true
			],
		],

		// Module Kartik-v Markdown Editor
		'markdown' => [
			'class' => 'kartik\markdown\Module',

			// array the the internalization configuration for this module
			'i18n' => [
				'class' => 'yii\i18n\PhpMessageSource',
				'basePath' => '@markdown/messages',
				'forceTranslation' => true
			],
		],
	],
    'components' => [
	    'SmtpSendEmail' => [
		    'class' => 'common\components\SmtpEmail'
	    ],
//        'cache' => [
//            'class' => 'yii\caching\FileCache',
//            'formatter' => [
//                'timeZone' => 'Europe/Minsk',
//                'dateFormat' => 'd.MM.Y',
//                'timeFormat' => 'H:mm:ss',
//                'datetimeFormat' => 'd.MM.Y H:mm',
//            ]
//        ],
    ],
];
