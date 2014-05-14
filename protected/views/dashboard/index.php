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
    <div id ="container-left">
	    <div id ="action-list">
	        <div class="action-icons firewall">
				<span class="action-icon">
				<a href="admin.php?page=ose_fw_manageips">
                      <img width="80" height="80" src="<?php echo OSE_FWURL; ?>/public/images/firewall.png" alt="">
                </a>      
				</span>
                <a href="admin.php?page=ose_fw_manageips">
				   <h4><?php echo FIREWALL; ?></h4>
                </a>
			</div>
		   <div class="action-icons vsscan">
			    <span class="action-icon">
			    <a href="admin.php?page=ose_fw_vsscan">
                     <img width="80" height="80" src="<?php echo OSE_FWURL; ?>/public/images/vsscanner.png" alt="">
                </a>     
			    </span>
                <a href="admin.php?page=ose_fw_vsscan">
			       <h4><?php echo OSE_VIRUS_SCAN; ?></h4>
                </a>
		   </div>
	     <div class="action-icons backup">
               <span class="action-icon">
		       <a href="admin.php?page=ose_fw_backup">
                     <img width="80" height="80" src="<?php echo OSE_FWURL; ?>/public/images/auditing.png" alt="">
               </a>     
			   </span>
			   <a href="admin.php?page=ose_fw_backup">
                   <h4><?php echo BACKUP; ?></h4>
               </a>    
	     </div>
	     <div class="action-icons config">
               <span class="action-icon">
		       <a href="admin.php?page=ose_fw_configuration">
                     <img width="80" height="80" src="<?php echo OSE_FWURL; ?>/public/images/configuration.png" alt="">
               </a>     
			   </span>
               <a href="admin.php?page=ose_fw_configuration">
			    <h4><?php echo CONFIGURATION; ?></h4>
               </a>
	     </div>          
	    </div>
	    <div id ="audit-list">
	    <?php 
	    	$this ->model->showStatus (); 
	    ?>
	    </div>
    </div>
	<div id = "container-right">
      <iframe frameborder="0" width="292px" scrolling="no" height="550px" name="f25a9d3bc489364" allowtransparency="true" title="fb:like_box Like us in Facebook " style="border: medium none; visibility: visible; width: 292px; height: 550px;" src="http://www.facebook.com/plugins/like_box.php?app_id=&amp;channel=http%3A%2F%2Fstatic.ak.facebook.com%2Fconnect%2Fxd_arbiter%2FdgdTycPTSRj.js%3Fversion%3D40%23cb%3Dfaa9b17456f606%26domain%3Dwww.centrora.com%26origin%3Dhttp%253A%252F%252Fwww.centrora.com%252Ff14adc948a9a856%26relation%3Dparent.parent&amp;force_wall=true&amp;header=false&amp;height=550&amp;href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FOSE-Firewall%2F359461984157157&amp;locale=en_US&amp;sdk=joey&amp;show_border=false&amp;show_faces=false&amp;stream=true&amp;width=292" class=""></iframe>
      <p><a href="https://twitter.com/loveCentrora" class="twitter-follow-button"  data-size="large" data-show-count="true" data-show-screen-name="true"></a></p>
      <div class="g-follow" data-annotation="bubble" data-height="24" data-href="https://plus.google.com/100825419799499224939" data-rel="publisher"></div>
      <div style="padding: 10px 0px;"><i class="icon-wordpress" style="color: #666; margin-right: 5px; "></i><a href = "http://wordpress.org/support/view/plugin-reviews/ose-firewall" target="_blank">Give our plugin a review</a></div>
    </div>
  </div>
</div>