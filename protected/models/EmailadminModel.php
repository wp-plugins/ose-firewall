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
class EmailadminModel extends ConfigurationModel {
	public function __construct() {
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
	protected function loadLibrary () {
		oseFirewall::loadEmails();
		oseFirewall::loadUsers();
	}
	public function getCHeader() {
		return oLang :: _get('EMAIL_ADMIN_TITLE');
	}
	public function getCDescription() {
		return oLang :: _get('EMAIL_ADMIN_DESC');
	}
	public function loadLocalscript () {
		$baseUrl = Yii :: app()->baseUrl;
		$cs = Yii :: app()->getClientScript();
		$this->loadJSLauguage ($cs, $baseUrl);
		$cs->registerScriptFile($baseUrl . '/public/js/emailadmin.js', CClientScript::POS_END);
	}
	public function getAdminEmailmap()
	{
		$oseEmail = new oseEmail('firewall');
		$return = array();
		if(oseFirewall::isDBReady()){
			$return['id']=1; 
			$return['results'] = $oseEmail->getAdminEmailList();
			if (empty($return['results']))
			{
				$return['results']['id'] = 0;
				$return['results']['user'] = 'N/A';
				$return['results']['subject'] = 'N/A';
			}		
			$return['total'] = COUNT($return['results']);
		}else{
			$return['id']=1;
			$return['results']['id'] = 0;
			$return['results']['user'] = 'N/A';
			$return['results']['subject'] = 'N/A';
			$return['total'] = 0;
		}
		return $return;  
	}
	public function getAdminUsers()
	{
		$oseUsers = new oseUsers('wordpress');
		$return = array();
		$return['id']=1; 
		$return['results'] = $oseUsers->getAdminUsers();
		$return['total'] = COUNT($return['results']);
		return $return;  
	}
	public function getEmailList()
	{
		$oseEmail = new oseEmail('firewall');
		$return = array();
		$return['id']=1; 
		$return['results'] = $oseEmail->getEmailList();
		$return['total'] = $oseEmail->getEmailListTotal();
		return $return;  
	}
	public function addadminemailmap($userid, $emailid)
	{
		$oseEmail = new oseEmail('firewall');
		return $oseEmail->addadminemailmap($userid, $emailid); 
	}
	public function deleteadminemailmap($ids)
	{
		$oseEmail = new oseEmail('firewall');
		foreach ($ids as $id)
		{
			$result = $oseEmail->deleteadminemailmap($id->id, $id->email_id);
			if ($result == false)
			{
				return false;
			}
		}
		return true; 
	}
}	