<?php
/**
 * @version     2.0 +
 * @package       Open Source Excellence Security Suite
 * @subpackage    Centrora Security Firewall
 * @subpackage    Open Source Excellence WordPress Firewall
 * @author        Open Source Excellence {@link http://www.opensource-excellence.com}
 * @author        Created on 01-Jun-2013
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 *
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSE_ADMINPATH') && !defined('_JEXEC'))
{
 die('Direct Access Not Allowed');
}

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

require_once (dirname(__FILE__).ODS."define.php"); 
return array(
	'basePath'=>dirname(__FILE__) . DIRECTORY_SEPARATOR.'..',
	'name'=>'OSE Firewall',
	'defaultController'=>'dashboard',
	// preloading 'log' component
	'preload'=>array(
		'log'
	),
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),
	/*
	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),
	*/
	// application components
	'components'=>array(
		/*
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		*/
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
					'levels'=>'trace',
				),
				array(
				    'class'=>'CWebLogRoute',
				        //
				        // I include *trace* for the 
				        // sake of the example, you can include
				        // more levels separated by commas
				    'levels'=>'trace',
				        //
				        // I include *vardump* but you
				        // can include more separated by commas
				    'categories'=>'vardump',
				        //
				        // This is self-explanatory right?
				    'showInFireBug'=>true
				)
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