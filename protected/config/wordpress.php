<?php 
defined('OSEFWDIR') or die;
define('OSEAPPDIR', OSEFWDIR);
define('OSE_FRAMEWORK', true);
define('OSE_FRAMEWORKDIR', OSEFWDIR . 'framework');
define('OSE_FWURL',plugins_url('',dirname(dirname(__FILE__))));

$plugins = parse_url(WP_PLUGIN_URL);
define('OSE_FWRELURL',$plugins['path'].'/ose-firewall');
define('OSE_FWASSETS', OSEFWDIR . DS . 'assets');
define('OSE_WPURL',rtrim(site_url(), '/') );
define('OSE_ADMINURL', OSE_WPURL.'/wp-admin/admin.php');  
define('OSE_FWCONTROLLERS', OSEFWDIR . 'protected' . DS . 'controllers');
define('OSE_FWFRAMEWORK', OSEFWDIR . DS . 'protected' . DS.'library'); 
define('OSE_FWLANGUAGE', OSEFWDIR . DS . 'public' . DS.'messages');
define('OSE_FWDATA', OSEFWDIR . DS . 'protected' . DS.'data'); 
define('OSE_DEFAULT_SCANPATH', ABSPATH);
?>