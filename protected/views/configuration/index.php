<?php
/**
 * @version     6.0 +
 * @package       Open Source Excellence Security Suite
 * @subpackage    Open Source Excellence CPU
 * @author        Open Source Excellence {@link http://www.opensource-excellence.com}
 * @author        Created on 30-Sep-2010
 * @author        Updated on 30-Mar-2013 
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @copyright Copyright (C) 2008 - 2010- ... Open Source Excellence
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
*/
defined('OSE_FRAMEWORK') or die("Direct Access Not Allowed");
?>
<div id = "oseappcontainer">
  <div class="container">
	<?php 
		$this ->model->showLogo ();
		$this ->model->showHeader ();
	?>
	
	<section class="ose-options">
          <ul >
            <li>
              	<a class ="obtn obtn-large obtn-blue" href="<?php echo $this->model->getURL('scanconfig');?>" >
	              <?php oLang::_('SCAN_CONFIGURATION'); ?>
	              <i class="icon-cog"></i>
	            </a> 
            </li>
            <li>
              	<a class ="obtn obtn-large obtn-green" href="<?php echo $this->model->getURL('avconfig');?>" >
	              <?php oLang::_('ANTIVIRUS_CONFIGURATION'); ?>
	              <i class="icon-search"></i>
	            </a> 
            </li>
            <li>
	            <a class ="obtn obtn-large obtn-red" href="<?php echo $this->model->getURL('seoconfig');?>" >
	              <?php oLang::_('SEO_CONFIGURATION'); ?>
				  <i class="icon-google"></i>	              
	            </a>  
            </li>
            <li>
	            <a class ="obtn obtn-large obtn-magenta" href="<?php echo $this->model->getURL('spamconfig');?>" >
	              <?php oLang::_('ANTISPAM_CONFIGURATION'); ?>
				  <i class="icon-spam"></i>	              
	            </a>  
            </li>
            <li>
                <a class ="obtn obtn-large obtn-purple" href="<?php echo $this->model->getURL('emailconfig');?>" >
	              <?php oLang::_('EMAIL_CONFIGURATION'); ?>
	              <i class="icon-mail-6"></i>
	            </a>    
            </li>
            <li>
                <a class ="obtn obtn-large obtn-yellow" href="<?php echo $this->model->getURL('emailadmin');?>" >
	              <?php oLang::_('EMAIL_ADMIN'); ?>
	              <i class="icon-user-8"></i>
	            </a>    
            </li>
          </ul>
    </section>
  </div>
</div>