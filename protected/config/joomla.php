<?php
defined('OSEFWDIR') or die;
define('OSEAPPDIR', OSEFWDIR);
define('OSE_FRAMEWORK', true);
define('OSE_FRAMEWORKDIR', OSEFWDIR . 'framework');
define('OSE_FWURL',JURI::root().'/administrator/components/com_ose_firewall/');

define('OSE_FWRELURL',JURI::root().'/administrator/components/com_ose_firewall/');
define('OSE_FWASSETS', OSEFWDIR . DS . 'assets');
define('OSE_WPURL',rtrim(JURI::base(), '/') );
define('OSE_ADMINURL', OSE_WPURL.'/index.php?option=com_ose_firewall');  
define('OSE_FWCONTROLLERS', OSEFWDIR . 'protected' . DS . 'controllers');
define('OSE_FWFRAMEWORK', OSEFWDIR . DS . 'protected' . DS.'library'); 
define('OSE_FWLANGUAGE', OSEFWDIR . DS . 'public' . DS.'messages');
define('OSE_FWDATA', OSEFWDIR . DS . 'protected' . DS.'data'); 
define('OSE_DEFAULT_SCANPATH', JPATH_SITE);
?>