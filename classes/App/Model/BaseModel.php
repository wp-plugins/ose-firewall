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
class BaseModel  {
	protected $db = null;
	protected $cent_nounce = "";
	public function __construct() {
		
	}
	public function getCHeader() {}
	public function getCDescription() {}
	public function showLogo () {
		oseFirewall :: showLogo();
	}
	protected function loadDatabase () {
		$this->db = oseFirewall::getDBO();
	}
	protected function loadCoreLibrary () {
		require_once (OSE_FWFRAMEWORK.ODS.'oseFirewallWordpress.php');
	}
	protected function loadLibrary () {
		$this->loadFirewallStat () ;
		oseFirewall::callLibClass('ipmanager', 'ipmanager');
	}
	protected function loadFirewallStat () {
		if (OSE_CMS == 'joomla')
		{
			oseFirewall::callLibClass('firewallstat', 'firewallstatJoomla');
		}
		else
		{
			oseFirewall::callLibClass('firewallstat', 'firewallstatWordpress');
		}
	}
	protected function loadJSLauguage ($cs, $baseUrl) {
        $lang = oseFirewallBase::getLocaleString();
		$cs->registerScriptFile($baseUrl . '/public/messages/'.$lang.'.js', CClientScript::POS_HEAD);
	}
	public function getNounce () {
		echo '<input type="hidden" id="centnounce" value ="'.oseFirewall::loadNounce().'" />';
	}
	public function showHeader () { 
		$html = '<div class="bs-callout bs-callout-info fade in">';
		$html .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
		$html .= '<h4>'.$this->getCHeader().'</h4>';
		$html .= '<p>'.$this->getCDescription ().'</p>';
		$html .= '</div>';
		echo $html; 
	}
	public function throwAjaxReturn ($result, $status, $msg, $continue) {
		oseAjax :: aJaxReturn($result, $status, $msg, $continue); 
	}
	public function throwAjaxRecursive ($result, $status, $msg, $continue, $step) {
		oseAjax :: throwAjaxRecursive($result, $status, $msg, $continue, $step); 
	}
	protected function transMessage ($success, $msg)
	{
		$style = ($success==true)?'ajax-success':'ajax-failed';
		return '<div class="'.$style.'" >'.$msg.'</div>';
	}
	protected function addPages($url, $action) {
		$query = 'SELECT `id`, `visits` FROM `#__osefirewall_pages` WHERE `page_url` = ' . $this->db->quoteValue($url);
		$this->db->setQuery($query);
		$results = $this->db->loadObject();
		if (empty ($results)) {
			$varValues = array (
				'id' => 'DEFAULT',
				'page_url' => $url,
				'action' => $action,
				'visits' => 1
			);
			$id = $this->db->addData('insert', '#__osefirewall_pages', null, null, $varValues);
		} else {
			$varValues = array (
				'visits' => $results->visits + 1
			);
			$id = $this->db->addData('update', '#__osefirewall_pages', 'id', $results->id, $varValues);
		}
		return $id;
	}
	protected function addReferer($referer=null) {
		$query = 'SELECT `id` FROM `#__osefirewall_referers` WHERE `referer_url` = ' . $this->db->quoteValue($referer);
		$this->db->setQuery($query);
		$results = $this->db->loadObject();
		if (empty ($results)) {
			$varValues = array (
				'id' => 'DEFAULT',
				'referer_url' => $referer
			);
			$id = $this->db->addData('insert', '#__osefirewall_referers', null, null, $varValues);
		} else {
			$id = $results->id;
		}
		return $id;
	}
	public function isDBReady(){
		$return = array ();
		$return['ready'] = oseFirewall :: isDBReady();
		$return['type'] = 'base';
		return $return['ready'];
	}
	public function returnJSON($results) {
		oseAjax::returnJSON($results);
	}
	public function loadRequest () {
		oseFirewall::loadRequest ();
	}
	public function getVar ($var, $default) {
		$this->loadRequest ();
		return oRequest :: getVar($var, $default);
	}
	public function getInt ($var, $default=0) {
		$this->loadRequest ();
		return oRequest :: getInt($var, $default);
	}
	public function getLang ($var) {
		return oLang::_get($var);
	}
	public function aJaxReturn($result, $status, $msg, $continue = false, $id = null) {
		oseAjax::aJaxReturn($result, $status, $msg, $continue = false, $id = null);
	}
	public function getFirewallIpManager () {
		$this->loadDatabase();
		return new oseFirewallIpManager($this->db);
	}
	protected function loadJSON () {
		if (!class_exists('oseJSON')) {
			oseFirewall::loadJSON();
		}
	}
	public function JSON_encode ($var) {
		$this->loadJSON ();
		return oseJSON::encode($var); 
	}
	public function JSON_decode ($var) {
		$this->loadJSON ();
		return oseJSON::decode($var);
	}
	public function showSelectionRequired () {
		$this->aJaxReturn(false, 'ERROR', $this->getLang("PLEASE_SELECT_ITEMS"), false);
	}
	public function fileClean ($path) {
		oseFirewall::loadFiles ();
		return oseFile::clean ($path);
	}
	protected function loadAllAssets () {
		// JS
		if (OSE_CMS =='joomla')
		{
			$version = new JVersion();
			if ($version->getShortVersion()<3 || $version->getShortVersion()>5)
			{	
				oseFirewall::loadJSFile ('CentroraJquery', 'jquery-1.11.1.min.js', false);
				oseFirewall::loadJSFile ('CentroraBootStrapJS', 'bootstrap.min.js', false);
			}
			else
			{
				JHtml::_('bootstrap.framework');
			}
			oseFirewall::loadJSFile ('CentroraJquery', 'joomla.js', false);
		}	
		else
		{
			oseFirewall::loadJSFile ('CentroraBootStrapJS', 'bootstrap.min.js', false);
		}
//        oseFirewall::loadJSFile ('CentroraDropboxJS', 'dropins.js', false);
		oseFirewall::loadJSFile ('CentroraDataTableJS', 'jquery.dataTables.min.js', false);
		oseFirewall::loadJSFile ('CentroraMaskInput','plugins/maskedinput/jquery.maskedinput.js', false);
		oseFirewall::loadJSFile ('CentroraMaskIP','plugins/maskedinput/jquery.input-ip-address-control-1.0.min.js', false);
		oseFirewall::loadJSFile ('CentroraBootbox', 'plugins/bootbox/bootbox.js', false);
		oseFirewall::loadJSFile ('CentroraForm', 'plugins/form/jquery.form.min.js', false);
		//oseFirewall::loadJSFile ('CentroraModernizr', 'modernizr.custom.js', false);
		oseFirewall::loadJSFile ('CentroraJResponse', 'jRespond.min.js', false);
		oseFirewall::loadJSFile ('CentroraSlimscroll', 'plugins/slimscroll/jquery.slimscroll.min.js', false);
		oseFirewall::loadJSFile ('CentroraSlimscrolHor', 'plugins/slimscroll/jquery.slimscroll.horizontal.min.js', false);
		oseFirewall::loadJSFile ('CentroraAppstart', 'jquery.appStart.js', false);
        oseFirewall::loadJSFile('CentroraInputMask', 'plugins/inputmask/jquery.mask.js', false);

        oseFirewall::callLibClass('oem', 'oem');
        $oem = new CentroraOEM();
        $oemCustomer = $oem->hasOEMCustomer();
        if ($oemCustomer) {
            oseFirewall::loadJSFile('oemJS', 'oem/' . $oemCustomer['data']['customer_id'] . '/custom.js', false);
        } else {
            oseFirewall::loadJSFile('CentroraColors', 'colors.js', false);
        };
		oseFirewall::loadJSFile ('CentroraApp', 'app.js', false);
        $lang = oseFirewallBase::getLocaleString();
		oseFirewall::loadLanguageJSFile ('CentroraLanguage', ''.$lang.'.js', false);
		// CSS
		oseFirewall::loadCSSFile ('CentroraDataTable', 'jquery.dataTables.min.css', false);
		oseFirewall::loadCSSFile ('CentroraBootStrap', 'bootstrap.css', false);
		oseFirewall::loadCSSFile ('CentroraWaitme', 'waitme.less.css', false);
		oseFirewall::loadCSSFile ('CentroraIcons', 'icons.css', false);
		oseFirewall::loadCSSFile ('CentroraBootStrapLess', 'main.css', false);
		oseFirewall::loadCSSFile ('CentroraV4Style', 'v4.css', false);
		oseFirewall::loadCSSURL ('CentroraV4Font','https://fonts.googleapis.com/css?family=Open+Sans%3A400italic%2C400%2C600%2C700%7CRoboto+Slab%3A400%2C300%2C700');
		$this->getOEMCss();
	}
	protected function getOEMCss () {
		oseFirewall::callLibClass('oem', 'oem');
		$oem = new CentroraOEM(); 
		$oem->loadCSS (); 
	}
	protected function getEmptyReturn () {
		$return  = array();
		$return['data']['id'] = 0;
		$return['data']['name'] = 'N/A';
		$return['recordsTotal'] = 0;
		$return['recordsFiltered']=0;
		return $return;
	}
	public function getConfiguration($type)
	{
		$this->loadFirewallStat () ;
		$oseFirewallStat = new oseFirewallStat();
		$results = $oseFirewallStat->getConfiguration($type);
		return $results;
	}
	public function isConfigurationDBReady($data)
	{
		require_once(OSE_FWFRAMEWORK.ODS.'oseFirewallBase.php');
		if(isset($data['blockCountry'] ) && $data['blockCountry'] == 1)
		{
			if(oseFirewallBase :: isCountryBlockConfigDBReady() == false)
			{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get('CONFIG_SAVECOUNTRYBLOCK_FAILE'), false);
			}
		}
		if(isset($data['adVsPatterns'] ) && $data['adVsPatterns'] == 1){
			if(oseFirewallBase :: isAdvancePatternConfigDBReady() == false)
			{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get('CONFIG_ADPATTERNS_FAILE'), false);
			}
		}
		if(isset($data['adRules'] ) && $data['adRules'] == 1)
		{
			if(oseFirewallBase :: isAdvanceSettingConfigDBReady() == false)
			{
				oseAjax::aJaxReturn(false, 'ERROR', oLang::_get('CONFIG_ADRULES_FAILE'), false);
			}
		}
	}
//    public function showGoogleSecret() {
//        require_once(OSE_FWFRAMEWORK.ODS.'googleAuthenticator'.ODS.'class_gauthenticator.php');
//        $gauthenticator = new GoogleAuthenticator();
//        $secret = $gauthenticator->create_secret();
//        $QRcode = $gauthenticator->get_qrcode();
//        $result = array(
//            'secret' => $secret,
//            'QRcode' => $QRcode
//        );
//        return $result;
//    }
	public function saveConfiguration($type, $data)
	{
		$this->loadFirewallStat () ;
		$this->isConfigurationDBReady($data);
		$oseFirewallStat = new oseFirewallStat();
		$result = $oseFirewallStat->saveConfiguration($type, $data);
		$this -> confAjaxReturn ($result);
	}
	public function confAjaxReturn ($result)
	{
		if ($result==true)
		{
			oseAjax::aJaxReturn(true, 'SUCCESS', oLang::_get('CONFIG_SAVE_SUCCESS'), true);
		}
		else
		{
			oseAjax::aJaxReturn(false, 'ERROR', oLang::_get('CONFIG_SAVE_FAILED'), false);
		}
	}
	public function loadFiles () {
		oseFirewall::loadFiles ();
	}
	public function getToken () {
		$panel = new panel ();
		$tokens = $panel->getToken();
		print_r($tokens);
	}
	public function showFooterJs() {
		oseFirewall::loadJSFile ('CentroraUpdate', 'update.js', false);
	}
	protected function getProductType () {
		if (class_exists('SConfig'))
		{
			$product = 'st';
		}
		else
		{
			$product = 'pl';
		}
		return $product; 
	}
}	