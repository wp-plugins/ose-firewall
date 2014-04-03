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
$this ->getModel();
$this ->model->loadLocalscript (); 
?>
<div id = "oseappcontainer">
  <div class="container">
	<?php 
		$this ->model->showLogo ();
		$this ->model->showHeader ();
		if(!oseFirewall :: isDBReady()){
			include_once(OSEAPPDIR.ODS.'protected'.ODS.'views'.ODS.'layouts'.ODS.'error.php');
		}
	?>
	<div id ='oseadantihackerRulesets'></div>
  </div>
  <img alt="" src = "<?php echo OSE_FWRELURL;?>/public/images/icons/fam/accept.png"> Active
  <img alt="" src = "<?php echo OSE_FWRELURL;?>/public/images/icons/fam/delete.png"> Inactive
</div>
