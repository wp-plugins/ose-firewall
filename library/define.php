<?php
defined('OSEFWDIR') or die;
define('OSEFWLANGUAGE', OSEFWDIR . DS . 'languages');
define('OSEFWASSETS', OSEFWDIR . DS . 'assets');
define('OSEFWLIBRARY', OSEFWDIR . DS . 'library');
define('OSEFWTEMPLATES', OSEFWDIR . DS . 'templates');
define('OSEFWURL',rtrim(site_url(), '/') . '/wp-content/plugins/ose-firewall');
define('OSEWPADMINURL',rtrim(site_url(), '/') . '/wp-admin');
define('OSEWPURL',rtrim(site_url(), '/') );
?>