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
		$this ->model->showStatus (); 
	?>
	<section class="ose-options">
          <ul >
            <li>
              	<a class ="obtn obtn-large obtn-blue" href="<?php echo OSE_ADMINURL.'?page=ose_fw_manageips';?>" >
	              <?php oLang::_('MANAGE_IPS'); ?>
	              <i class="icon-layers-alt"></i>
	            </a> 
            </li>
            <li>
              	<a class ="obtn obtn-large obtn-green" href="<?php echo OSE_ADMINURL.'?page=ose_fw_configuration';?>" >
	              <?php oLang::_('CONFIGURATION'); ?>
	              <i class="icon-tools"></i>
	            </a> 
            </li>
            <li>
	            <a class ="obtn obtn-large obtn-red" href="<?php echo OSE_ADMINURL.'?page=ose_fw_rulesets';?>" >
	              <?php oLang::_('RULESETS'); ?>
				  <i class="icon-list-3"></i>	              
	            </a>  
            </li>
            <li>
	            <a class ="obtn obtn-large obtn-magenta" href="<?php echo OSE_ADMINURL.'?page=ose_fw_variables';?>" >
	              <?php oLang::_('MANAGE_VARIABLES'); ?>
				  <i class="icon-layout"></i>	              
	            </a>  
            </li>
            <li>
                <a class ="obtn obtn-large obtn-purple" href="<?php echo OSE_ADMINURL.'?page=ose_fw_vsscan';?>" >
	              <?php oLang::_('OSE_VIRUS_SCAN'); ?>
	              <i class="icon-bug"></i>
	            </a>    
            </li>
            <li>
                <a class ="obtn obtn-large obtn-yellow" href="<?php echo OSE_ADMINURL.'?page=ose_fw_vsreport';?>" >
	              <?php oLang::_('VIRUS_SCAN_REPORT'); ?>
	              <i class="icon-untitled"></i>
	            </a>    
            </li>
          </ul>
    </section>
	<section class="compatibility">
          <h2><?php oLang::_('COMPATIBILITY'); ?></h2>
          <ol class="browsers">
            <li id="chrome" class="obtn-median obtn-yellow">
              <p><i class="icon-chrome"></i> Google Chrome 12+ </p>
            </li>
            <li id="firefox" class="obtn-median obtn-orange">
              <p><i class="icon-firefox"></i> Firefox 5+</p>
            </li>
            <li id="safari" class="obtn-median obtn-blueDark">
              <p><i class="icon-safari"></i> Safari 5+</p>
            </li>
            <li id="opera" class="obtn-median obtn-red">
              <p><i class="icon-opera"></i> Opera 11+</p>
            </li>
            <li id="ie" class="obtn-median obtn-blue">
              <p><i class="icon-IE"></i> Internet Explorer 9+</p>
            </li>
          </ol>
    </section>
    <section class="compatibility">
     	<h2><?php oLang::_('OSE_FOLLOWUS'); ?></h2>
	     <div class="social-icons">
			<a class ="obtn-small obtn-blue" href="https://www.facebook.com/pages/OSE-Firewall/359461984157157" target="_blank"><i class="icon-facebook"></i> Facebook</a>
			<a class="obtn-small obtn-blueDark" href="https://twitter.com/ProtectWebsite" target="_blank"><i class="icon-twitter	"></i> Twitter</a>
			<a class="obtn-small obtn-orange" href="https://plus.google.com/u/0/100825419799499224939/posts" target="_blank"><i class="icon-googleplus	"></i> Google+</a>
		</div>
    </section>
			
	</div>
</div>
