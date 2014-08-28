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
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
 	die('Direct Access Not Allowed');
}

define('OSEAPPDIR', OSEFWDIR);
define('OSE_FRAMEWORK', true);
define('OSE_FRAMEWORKDIR', OSEFWDIR . 'framework');
define('OSE_FWURL',OURI::root().'components/com_ose_firewall/');

define('OSE_FWRELURL',OURI::root().'components/com_ose_firewall/');
define('OSE_FWASSETS', OSEFWDIR . ODS . 'assets');
define('OSE_WPURL',rtrim(OURI::base(), '/') );
define('OSE_ADMINURL', OSE_WPURL.'/index.php?option=com_ose_firewall');
define('OSE_FWRECONTROLLERS' , OSEFWDIR . 'protected' . ODS . 'controllers' . ODS . 'remoteControllers');  
define('OSE_FWCONTROLLERS', OSEFWDIR . 'protected' . ODS . 'controllers');
define('OSE_FWFRAMEWORK', OSEFWDIR . ODS . 'protected' . ODS.'library'); 
define('OSE_FWLANGUAGE', OSEFWDIR . ODS . 'public' . ODS.'messages');
define('OSE_FWDATA', OSEFWDIR . ODS . 'protected' . ODS.'data'); 
define('OSE_DEFAULT_SCANPATH', dirname(dirname(dirname(OSEFWDIR))));
define('DB_BACKUP_DOWNLOAD_URL', OSE_WPURL . '/index.php?option=com_ose_firewall&task=downloadBackupDB&action=downloadBackupDB&controller=backup&ids=');
define('FILE_BACKUP_DOWNLOAD_URL', OSE_WPURL . '/index.php?option=com_ose_firewall&task=downloadBackupFile&action=downloadBackupFile&controller=backup&ids=');
define('EXPORT_DOWNLOAD_URL', OSE_WPURL . '/index.php?option=com_ose_firewall&task=downloadCSV&action=exportCSV&controller=manageips&filename=');
?>