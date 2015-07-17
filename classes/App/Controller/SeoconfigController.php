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
class SeoconfigController extends ConfigurationController {
	public function action_SaveConfigSEO () {
		$this->model->loadRequest (); 
		$type = $this->model->getVar('type', null);
		if (empty($type)) {return;}
		$data = array();
		$data['pageTitle'] = $this->model->getVar('pageTitle', null);
		$data['metaKeywords'] = $this->model->getVar('metaKeywords', null);
		$data['metaDescription'] = $this->model->getVar('metaDescription', null);
		$data['metaGenerator'] = $this->model->getVar('metaGenerator', null);
		$data['scanGoogleBots'] = $this->model->getInt('scanGoogleBots', 0);
        $data['scanMsnBots'] = $this->model->getInt('scanMsnBots', 0);
		$data['scanYahooBots'] = $this->model->getInt('scanYahooBots', 0);
		$this->model->saveConfiguration($type, $data);
	}
}
?>	