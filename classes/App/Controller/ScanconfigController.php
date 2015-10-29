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
        $pattern = "/^[a-zA-Z\d]+$/";
        $secureKey = $this->model->getVar('secureKey', null);
        $loginSlug = $this->model->getVar('loginSlug', null);
        if(!empty($secureKey)){
        if (!preg_match($pattern, $secureKey)) {
            $result = array();
            $result['status'] = 'Completed';
            $result['message'] = 'Backend Access Secure Key can only contain numbers, letters';
            $this->model->returnJSON($result);
        }}
        if(!empty($loginSlug)){
            if (!preg_match($pattern, $loginSlug)) {
                $result = array();
                $result['status'] = 'Completed';
                $result['message'] = 'Login Slug can only contain numbers and letters';
                $this->model->returnJSON($result);
            }}
        $data = array();
		switch ($type)
		{
			case 'scan':
                $data['secureKey'] = $this->model->getVar('secureKey', null);
                $data['loginSlug'] = $this->model->getVar('loginSlug', null);
                $data['devMode'] = $this->model->getInt('devMode', 1);
                $data['strongPassword'] = $this->model->getInt('strongPassword', 0);
				$data['scanUpFiles'] = $this->model->getInt('scanUpFiles', null);
				$data['blockIP'] = $this->model->getInt('blockIP', 0);
				$data['customBanpage'] = $_POST['customBanpage'];
				$data['customBanURL'] = $this->model->getVar('customBanURL',null);
				break;
            case 'admin':
                $data['adminEmail'] = $this->model->getVar('adminEmail', null);
                $data['receiveEmail'] = $this->model->getInt('receiveEmail', 0);
                $data['gaSecret'] = $this->model->getVar('GA_secret', null);
                $data['centroraGA'] = $this->model->getVar('centroraGA', 0);
                break;
            case 'advscan':
				$data['adRules'] = $this->model->getInt('adRules', 0);
				$data['threshold'] = $this->model->getInt('threshold', 20);
				$data['slient_max_att'] = $this->model->getInt('slient_max_att', 10);
				$data['silentMode'] = $this->model->getInt('silentMode', 0);
				$data['blockCountry'] = $this->model->getInt('blockCountry', 0);
                $data['clearCronKey'] = $this->model->getVar('clearCronKey', null);
                break;
            case 'bf':
                $data['bf_status'] = $this->model->getInt('bf_status', 0);
                $data['loginSec_maxFailures'] = $this->model->getInt('loginSec_maxFailures', 20);
                $data['loginSec_countFailMins'] = $this->model->getInt('loginSec_countFailMins', 5);
                $data['googleVerification'] = $this->model->getVar('googleVerification', 0);
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
        $oemConfArray = $this->model->getConfiguration('oem');
        if (!empty($oemConfArray['data']['customer_id'])) {
            $single = array();
            $single['passcode_status'] = $this->model->getInt('passcode_status', 0);
            $this->model->saveConfigurationNoExit('oem', $single);
        }
        if (OSE_CMS == 'joomla') {
            $totp = $this->model->getInt('totp', 0);
            $this->model->updatetotp($totp);
        }
        if($type == 'scan') {
            $confArray = $this->model->getConfiguration('scan');
            if (OSE_CMS == 'wordpress') {
                if ($confArray['data']['loginSlug'] == $data['loginSlug']) {
                    $this->model->saveConfiguration($type, $data);
                } else {
                    $this->model->saveConfigurationNoExit($type, $data);
                    $result = array();
                    $result['status'] = 'Completed';
                    if (!empty($data['loginSlug'])) {
                        $result['message'] = 'Your login page is now: <code>' . $this->model->getLoginUrl($data['loginSlug']) . '</code>, please bookmark now';
                    } else {
                        $result['message'] = 'Your login page is now: <code>' . home_url() . '/wp-login.php?' . '</code>, please bookmark now';

                    }
                    $this->model->sendemail('loginSlug', $result['message']);
                    $this->model->returnJson($result);
                }
            } else {
                if ($confArray['data']['secureKey'] == $data['secureKey']) {
                    $this->model->saveConfiguration($type, $data);
                } else {
                    $this->model->saveConfigurationNoExit($type, $data);
                    $result = array();
                    $result['status'] = 'Completed';
                    if (!empty($data['secureKey'])) {
                        $result['message'] = 'Your administrator page is now: <code>' . 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?' . $data['secureKey'] . '</code>  please bookmark now.';
                    } else {
                        $result['message'] = 'Your administrator page is now: <code>' . 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '</code>  please bookmark now.';

                    }
                    $this->model->sendemail('secureKey', $result['message']);
                    $this->model->returnJson($result);
                }
            }
        } else {
            $this->model->saveConfiguration($type, $data);
        }
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