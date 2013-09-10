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
class SpamconfigModel extends ConfigurationModel {
	public function __construct() {
		$this->loadLibrary ();
		$this->loadDatabase ();
	}
	public function getCHeader() {
		return oLang :: _get('ANTISPAM_CONFIGURATION_TITLE');
	}
	public function getCDescription() {
		return oLang :: _get('ANTISPAM_CONFIGURATION_DESC');
	}
	public function loadLocalscript () {
		$lang = oseFirewall::getLocale (); 
		$baseUrl = Yii :: app()->baseUrl;
		$cs = Yii :: app()->getClientScript();
		$cs->registerScriptFile($baseUrl . '/public/messages/'.$lang.'.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile($baseUrl . '/public/js/spamconfig.js', CClientScript::POS_END);
	}
}	