<?php
namespace App\Controller;
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
class ScanconfigController extends ConfigurationController {
	public function action_SaveConfigScan () {
		$this->model->loadRequest (); 
		$type = $this->model->getVar('type', null);
		if (empty($type)) {return;}
		$data = array();
		switch ($type)
		{
			case 'scan':
				$data['devMode'] = $this->model->getInt('devMode', 1);
                $data['strongPassword'] = $this->model->getInt('strongPassword', 0);
				$data['allowExts'] = $this->model->getVar('allowExts', null);
				$data['scanUpFiles'] = $this->model->getInt('scanUpFiles', null);
				$data['blockIP'] = $this->model->getInt('blockIP', 0);
				$data['adminEmail'] = $this->model->getVar('adminEmail', null);
				$data['receiveEmail'] = $this->model->getInt('receiveEmail', 0);
				$data['googleVerification'] = $this->model->getVar('googleVerification', 0);
                $data['gaSecret'] = $this->model->getVar('GA_secret', null);
				$data['customBanpage'] = $_POST['customBanpage'];
				$data['customBanURL'] = $this->model->getVar('customBanURL',null);
				break;
			case 'advscan':
				$data['adRules'] = $this->model->getInt('adRules', 0);
				$data['threshold'] = $this->model->getInt('threshold', 20);
				$data['slient_max_att'] = $this->model->getInt('slient_max_att', 10);
				$data['silentMode'] = $this->model->getInt('silentMode', 0);
				$data['blockCountry'] = $this->model->getInt('blockCountry', 0);
				break;
			case 'communicate':
				$data['auditReport'] = $this->model->getInt('auditReport', 1);
				break;
			case 'country':
				$data['blockCountry'] = $this->model->getVar('blockCountry', 0);
				break;
			case 'schedule':
				$data['scheduleScan'] = $this->model->getInt('scheduleScan', 0);
				break;
			case 'phpconfig':
				$data['registerGlobalOff'] = $this->model->getInt('registerGlobalOff', 0);
				$data['safeModeOff'] = $this->model->getInt('safeModeOff', 0);
				$data['urlFopenOff'] = $this->model->getInt('urlFopenOff', 0);
				$data['displayErrorsOff'] = $this->model->getInt('displayErrorsOff', 0);
				$data['phpFunctionsOff'] = $this->model->getInt('phpFunctionsOff', 0);
				break;
		}
		//$data['scanFileVirus'] = $this->model->getInt('scanFileVirus', 0);
		//$data['showBadge'] = $this->model->getInt('showBadge', 0);
		//$data['badgeCSS'] = $this->model->getVar('badgeCSS', null);
		//$data['adRules'] = $this->model->getVar('adRules', 0);
		//$data['scanClamav'] = $this->model->getInt('scanClamav', 0);
		$this->model->saveConfiguration($type, $data);
	}

    public function action_checkPassword()
    {
        $result = $this->model->checkPassword();
        $this->model->returnJSON($result);
    }

    public function action_savePassword()
    {
        $this->model->loadRequest();
        $mpl = $this->model->getInt('mpl', 4);
        $pmi = $this->model->getInt('pmi', 0);
        $pms = $this->model->getInt('pms', 0);
        $pucm = $this->model->getInt('pucm', 0);
        $result = $this->model->savePassword($mpl, $pmi, $pms, $pucm);
        $this->model->returnJson($result);
    }
    public function action_showGoogleSecret()
    {
        $result = $this->model->showGoogleSecret();
        $this->model->returnJSON($result);
    }
}
?>	