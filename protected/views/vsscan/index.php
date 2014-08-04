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
$this ->getModel();
$this ->model->loadLocalscript (); 
$this ->model->getNounce(); 
?>

<div id = "oseappcontainer">
  <div class="container">
	<?php 
		$this ->model->showLogo ();
		$this ->model->showHeader ();
		if(oseFirewall :: isDBReady()){
	?>
		<?php echo '<script type="text/javascript"> var totalFiles = "'. $this->model->getTotalFiles().'"; </script>'; ?>
		<?php 
			//include (OSEAPPDIR.ODS.'protected'.ODS.'views'.ODS.'layouts'.ODS.'advpatterns.php')
		?>
        <div id = "scan-window"> 
        	<div id = "scanbuttons">
				<button id="vsstop" class='obtn obtn-small obtn-blue'><?php oLang::_('STOP_VIRUSSCAN') ?></button>
			    <button id="vscont" class='obtn obtn-small obtn-blue'><?php oLang::_('O_CONTINUE_SCAN') ?></button>
			    <button id="vsscan" class='obtn obtn-small obtn-blue'><?php oLang::_('START_NEW_VIRUSSCAN') ?></button>
	        </div>
	        <div id='progress-bar'></div> 
	        
	        <div id="p4" style="width:300px;"></div>
	        <div id ="scan_progress" class="content-description">
	            <b>Status:</b> <span id="p4text"> <?php echo $this->model->getTotalInfected(); ?></span>
	        </div>
	        <div id ="last_file">&nbsp;</div>
        </div>	
     <?php }else{
     	include(OSEAPPDIR.ODS.'protected'.ODS.'views'.ODS.'layouts'.ODS.'error.php');
     }?>
   </div>
</div>