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
require_once('BaseModel.php');
class DashboardModel extends BaseModel {
	public function __construct() {
		$this->loadLibrary ();
	}
	public function loadLocalScript() {
		$this->loadAllAssets ();
		oseFirewall::loadJSFile ('CentroraManageJQPlot', 'plugins/pie-chart/jquery.flot.custom.js', false);
		oseFirewall::loadJSFile ('CentroraVectorMap', 'plugins/vectormaps/jquery-jvectormap-1.2.2.min.js', false);
		oseFirewall::loadJSFile ('CentroraVectorWorldMap', 'plugins/vectormaps/maps/jquery-jvectormap-world-mill-en.js', false);
		oseFirewall::loadJSFile ('CentroraDashboard', 'dashboard.js', false);
	}
	public function getCHeader() {
		return oLang :: _get('DASHBOARD_TITLE');
	}
	public function getCDescription() {
		return oLang :: _get('OSE_WORDPRESS_FIREWALL_UPDATE_DESC');
	}
	public function showHeader () { 
		
	}
	public function isDBReady() {
		$return = array ();
		$return['ready'] = oseFirewall :: isDBReady();
		$return['type'] = 'base';
		return $return;
	}
	public function isDevelopModelEnable(){
		$audit = new oseFirewallAudit (); 
		$audit -> isDevelopModelEnable(true);
	}
	public function getCountryStat () {
		$oseFirewallStat = new oseFirewallStat();
		return $oseFirewallStat->getCountryStat();
	}
	public function getTrafficData () {
		$oseFirewallStat = new oseFirewallStat();
		return $oseFirewallStat->getTrafficData();
	}

    public function getPageUrl($page)
    {
        $url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $query = $_SERVER['QUERY_STRING'];
        if (OSE_CMS == "wordpress") {
            switch ($page) {
                case 'ipmanage':
                    $replace = "page=ose_fw_manageips";
                    echo str_replace($query, $replace, $url);
                    break;
                case 'scanResult':
                    $replace = "page=ose_fw_scanreport";
                    echo str_replace($query, $replace, $url);
                    break;
                case 'backup':
                    $replace = "page=ose_fw_backup";
                    echo str_replace($query, $replace, $url);
                    break;
            }
        } else {
            $joomla = "view=dashboard";
            switch ($page) {
                case 'ipmanage':
                    $replace = "view=manageips";
                    echo str_replace($joomla, $replace, $url);
                    break;
                case 'scanResult':
                    $replace = "view=vsreport";
                    echo str_replace($joomla, $replace, $url);
                    break;
                case 'backup':
                    $replace = "view=backup";
                    echo str_replace($joomla, $replace, $url);
                    break;
            }
        }
    }
    public function getMalwareMap()
    {
        oseFirewall::callLibClass('vsscanstat', 'vsscanstat');
        $scanReport = new oseVsscanStat ();
        $response = $scanReport->getMalwareMap();
        return $response;
    }

    public function getBackupList()
    {
        oseFirewall::callLibClass('backup', 'oseBackup');
        $backupResult = new oseBackupManager ();
        $response = $backupResult->getBackupList();
        return $response;
    }
	public function checkWebBrowsingStatus () {
		oseFirewall::callLibClass('panel','panel');
		$panel = new panel ();
		$response = $panel->checkSafebrowsing();
		return $response;
	}
}