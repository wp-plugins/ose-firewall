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
$clamdStatus =  $this ->model-> getClamdStatus();
?>

<div id = "oseappcontainer">
  <div class="container">
	<?php 
		$this ->model->showLogo ();
		$this ->model->showHeader ();
		if(oseFirewall :: isDBReady()){
	?>
		
  <div id ='vsconf'>
   <div id='clamavcanning'>
    <div class='setting' id='vssetting'>
     <div class="clamAVlogo">&nbsp;</div>
     
     <div class="header">
      <?php 
       		oLang::_('CLAMAV_STATUS');
      ?> 
     </div>
    
     <div class="items">
      <?php 
      		echo (isset($clamdStatus->status_desc))?$clamdStatus->status_desc:"N/A";
      ?>
     </div>
     
     <div class="header">
      <?php 
      	 	oLang::_('ACTION_PANEL'); 
      ?> 
     </div>
     <div class="items">
     <?php 
     		//oLang::_('RELOAD_DB_DESC');
     ?> 
     </div> 
     <!--<div class="items">
      <?php if ($clamdStatus->status == true)
      {
       ?>
         <button id="reload" class='button'><?php echo 'RELOAD_DB'; ?></button>
      <?php 
      }
      else
      {
       // echo "N_A"; 
       //echo "<input type = 'hidden' id='reload'> "; 
      }
      ?>   
     </div>
    --></div>
   </div> 
   
   <div id='virusscanning'>
     <dl class="vsitems">
      <dt class='info'>
      <?php 
       		oLang::_('CLAMAV_DEF_VERSION');
      ?> 
      </dt>
      <dt class='content'>       
          <?php echo (isset($clamdStatus->version))?$clamdStatus->version: "N/A";?>
      </dt>
     </dl> 
            
     <?php 
     if (isset($clamdStatus->stat) && !empty($clamdStatus->stat))
     {
     
      foreach ($clamdStatus->stat as $key => $stat)
      {
      ?>
      
       <dl class="vsitems">
        <dt class='info'>
        <?php 
         echo $key; 
        ?> 
        </dt>
        <dt class='content'>       
           <?php echo $stat?>
        </dt>
       </dl> 
      <?php  
      }
      } 
     ?>
   </div>
 </div>
 	
     <?php }else{
     	include(OSEAPPDIR.ODS.'protected'.ODS.'views'.ODS.'layouts'.ODS.'error.php');
     }?>
   </div>
</div>