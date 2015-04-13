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
class PermconfigController extends \App\Base {
	public function action_GetDirFileList() {
		if (isset($_REQUEST['mobiledevice']))
		{
			$mobiledevice = $this->model->getInt('mobiledevice', 0);
		}
		else
		{
			$mobiledevice = 0;
		}
		$results = $this ->model->getDirFileList();
		$this->model->returnJSON($results, $mobiledevice);
	}
    public function action_getFileTree() {

        $results = $this ->model->getFileTree();
        exit;
    }
    public function action_editPerms(){
        $result = $this ->model->editPerms();

        if ($result==true)
        {
            $this->model->aJaxReturn(true, 'SUCCESS', $this->model->getLang("Successfully changed File/Folder permissions."), false);
        }
        else
        {
            $this->model->aJaxReturn(false, 'ERROR', $this->model->getLang("The change permission operation was unsuccessful."), false);
        }
    }

	
}
?>