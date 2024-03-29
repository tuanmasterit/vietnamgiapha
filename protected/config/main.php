<?php

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name' => 'Yii Skeleton Application',
	'defaultController'=>'site',
	
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.helpers.*',
		'application.components.behaviors.*',
		'application.components.widgets.*',
		'application.extensions.*',
		'application.extensions.nestedset.*',
		'application.controllers.AdminController',
	),
	'preload'=>array('log'),
	'modules'=>array('textedit', 'GiaPha'),
	// application components
	'components'=>array(
//		'session' => array(
//			'class' => 'system.web.CDbHttpSession',
//			'connectionID' => 'db',
//		),
		'db'=>array(
			'class'=>'CDbConnection',
			'connectionString'=>'mysql:host=localhost;dbname=giapha',
			'username'=>'root',
			'password'=>'',
		),
		'email'=>array(
			'class'=>'application.extensions.email.Email',
			'delivery'=>'debug',
		),
		'user'=>array(
			'class'=>'application.components.WebUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'loginUrl'=>array('user/login'),
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CWebLogRoute',
					'levels'=>'info, error, warning',
					'categories'=>'system.*',
				),
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'trace, error, warning, watch',
					'categories'=>'system.db.*',
				),
			),
		),

		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
			//'caseSensitive'=>false,
			'rules'=>array(
				'user/register/*'=>'user/create',
				'user/settings/*'=>'user/update',
			),
		),
		'CLinkPager' => array(
			'class'=>'CLinkPager',
			'cssFile'=>false,
		),
		
		'rbac' => array(
            'class'=>'application.components.Rbac',
        ),		
	),
	
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'poppitypop@gmail.com',
	),
);