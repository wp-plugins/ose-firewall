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
$this ->getModel();
$this ->model->loadLocalscript (); 
?>
<div id = "oseappcontainer">
  <div class="container">
	<?php 
		$this ->model->showLogo ();
		$this ->model->showHeader ();
	?>
		<div id = "scannedInfo" class ="warning"><?php echo $this->model->getTotalFiles();?></div>
		<div>
			<button id="init" class='obtn obtn-large obtn-blue'><?php oLang::_('START_DB_INIT') ?></button>
		</div>
		<div>
            <button id="vsscan" class='obtn obtn-large obtn-red'><?php oLang::_('START_NEW_VIRUSSCAN') ?></button><br>
        </div>
        <div id="p4" style="width:300px;"></div>
        <div class="content-description">
            <b>Status:</b> <span id="p4text"> <?php echo $this->model->getTotalInfected(); ?></span>
        </div>	
	</div>
</div>