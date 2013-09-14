<?php
/**
* @version     2.0 +
* @package       Open Source Excellence Security Suite
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
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
class EmailconfigModel extends ConfigurationModel {
	public function __construct() {
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
	protected function loadLibrary () {
		oseFirewall::loadEmails();
	}
	public function getCHeader() {
		return oLang :: _get('EMAIL_CONFIGURATION_TITLE');
	}
	public function getCDescription() {
		return oLang :: _get('EMAIL_CONFIGURATION_DESC');
	}
	public function loadLocalscript () {
		$baseUrl = Yii :: app()->baseUrl;
		$cs = Yii :: app()->getClientScript();
		$this->loadJSLauguage ($cs, $baseUrl);
		$cs->registerScriptFile($baseUrl . '/public/js/emailconfig.js', CClientScript::POS_END);
	}
	public function getEmails()
	{
		$oseEmail = new oseEmail('firewall');
		$return = array();
		$return['id']=1; 
		$return['results'] = $oseEmail->getEmailList();
		if (empty($return['results']))
		{
			$return['results']['id'] = 0;
			$return['results']['subject'] = 'N/A';
		}
		$return['total'] = $oseEmail->getEmailListTotal();
		return $return;  
	}
	public function getEmailParams($id)
	{
		$oseEmail = new oseEmail('firewall');
		$return = array();
		$return['id']=1; 
		$return['results'] = $oseEmail->getEmailParams($id);
		$return['total'] = COUNT($return['results']);
		return $return;  
	}
	public function getEmail($id)
	{
		$oseEmail = new oseEmail('firewall');
		$return = array();
		$return['id']=1; 
		$return['results'] = $oseEmail->getEmail($id);
		$return['total'] = COUNT($return['results']);
		return $return;  
	}
	public function saveemail($id, $emailType, $emailBody, $emailSubject)
	{
		$oseEmail = new oseEmail('firewall');
        return  $oseEmail->saveemail($id, $emailType, $emailBody, $emailSubject);
	}
}	