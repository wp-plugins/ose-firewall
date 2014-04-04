<?php 
defined('OSEFWDIR') or die;
define('OSEAPPDIR', OSEFWDIR);
define('OSE_FRAMEWORK', true);
define('OSE_FRAMEWORKDIR', OSEFWDIR . 'framework');
define('OSE_FWURL',plugins_url('',dirname(dirname(__FILE__))));
define('OSE_ABSPATH', dirname(dirname(dirname(OSEFWDIR)))); 

$plugins = parse_url(WP_PLUGIN_URL);
define('OSE_FWRELURL',$plugins['path'].'/ose-firewall');
define('OSE_FWASSETS', OSEFWDIR . ODS . 'assets');
define('OSE_WPURL',rtrim(site_url(), '/') );
define('OSE_ADMINURL', OSE_WPURL.'/wp-admin/admin.php');  
define('OSE_FWRECONTROLLERS' , OSEFWDIR . 'protected' . ODS . 'controllers' . ODS . 'remoteControllers');
define('OSE_FWCONTROLLERS', OSEFWDIR . 'protected' . ODS . 'controllers');
define('OSE_FWFRAMEWORK', OSEFWDIR . 'protected' . ODS.'library'); 
define('OSE_FWLANGUAGE', OSEFWDIR . ODS . 'public' . ODS.'messages');
define('OSE_FWDATA', OSEFWDIR . 'protected' . ODS.'data'); 
define('OSE_DEFAULT_SCANPATH', ABSPATH);
define('DB_BACKUP_DOWNLOAD_URL', OSE_WPURL . '/wp-admin/admin-ajax.php?option=ose_firewall&task=downloadBackupDB&action=downloadBackupDB&controller=backup&ids=');
define('FILE_BACKUP_DOWNLOAD_URL', OSE_WPURL . '/wp-admin/admin-ajax.php?option=ose_firewall&task=downloadBackupFile&action=downloadBackupFile&controller=backup&ids=');
?>