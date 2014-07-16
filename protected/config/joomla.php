<?php
defined('OSEFWDIR') or die;
define('OSEAPPDIR', OSEFWDIR);
define('OSE_FRAMEWORK', true);
define('OSE_FRAMEWORKDIR', OSEFWDIR . 'framework');
define('OSE_FWURL',OURI::root().'components/com_ose_firewall/');

define('OSE_FWRELURL',OURI::root().'components/com_ose_firewall/');
define('OSE_FWASSETS', OSEFWDIR . ODS . 'assets');
define('OSE_WPURL',rtrim(OURI::base(), '/') );
define('OSE_ADMINURL', OSE_WPURL.'/index.php?option=com_ose_firewall');  
define('OSE_FWCONTROLLERS', OSEFWDIR . 'protected' . ODS . 'controllers');
define('OSE_FWFRAMEWORK', OSEFWDIR . ODS . 'protected' . ODS.'library'); 
define('OSE_FWLANGUAGE', OSEFWDIR . ODS . 'public' . ODS.'messages');
define('OSE_FWDATA', OSEFWDIR . ODS . 'protected' . ODS.'data'); 
define('OSE_DEFAULT_SCANPATH', dirname(dirname(dirname(OSEFWDIR))));

define('DB_BACKUP_DOWNLOAD_URL', OSE_WPURL . '/index.php?option=com_ose_firewall&task=downloadBackupDB&action=downloadBackupDB&controller=backup&ids=');
define('FILE_BACKUP_DOWNLOAD_URL', OSE_WPURL . '/index.php?option=com_ose_firewall&task=downloadBackupFile&action=downloadBackupFile&controller=backup&ids=');
?>