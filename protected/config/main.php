<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

require_once (dirname(__FILE__).DS."define.php"); 
return array(
	'basePath'=>dirname(__FILE__) . DIRECTORY_SEPARATOR.'..',
	'name'=>'OSE Firewall',
	'defaultController'=>'dashboard',
	// preloading 'log' component
	'preload'=>array('log'),
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'request'=>array(
                        //'class'=>'WPHttpRequest',
                        'baseUrl'=> OSE_FWURL,
                        'scriptUrl'=>  OSE_FWRELURL.'/ose_wordpress_firewall.php',
                ),
                // uncomment the following to enable URLs in path-format
        'assetManager'=>array(
	                'basePath'=>OSE_FWASSETS,
	                'baseUrl'=>OSE_FWRELURL.'/assets',
           		),
        'urlManager'=>array(
                        //'urlFormat'=>'path',
                        //'routeVar'=>'page',
        ),
        /*                
		'db'=>array(
			'connectionString' => 'mysql:host='.$wpdb->dbhost.';dbname='.$wpdb->dbname,
			'emulatePrepare' => true,
			'username' => $wpdb->dbuser,
			'password' => $wpdb->dbpassword,
			'charset' => 'utf8',
		),
		*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'dashboard/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);