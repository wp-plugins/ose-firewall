<?php
defined('OSEFWDIR') or die;
define('OSEFWLANGUAGE', OSEFWDIR . DS . 'languages');
define('OSEFWASSETS', OSEFWDIR . DS . 'assets');
define('OSEFWLIBRARY', OSEFWDIR . DS . 'library');
define('OSEFWTEMPLATES', OSEFWDIR . DS . 'templates');
define('OSEFWURL',plugins_url('',dirname(__FILE__)).DS);
define('OSEWPADMINURL',rtrim(site_url(), '/') . '/wp-admin');
define('OSEWPURL',rtrim(site_url(), '/') );
?>