<div id="container">
	<div id = "stage">
	<div>
		<h1> <?php _e(OSE_WORDPRESS_FIREWALL, 'ose_wordpress_firwall'); ?></h1>
		<p> <?php _e(OSE_WORDPRESS_FIREWALL_SETTING_DESC, 'ose_wp_firewall'); ?></p>
	</div>	
	<section style="" class="ose-options">
          <h2><?php _e(PLEASE_CHOOSE_OPTION, 'ose_wordpress_firwall'); ?></h2>
          <ul >
            <li id="firewall" >
              <div class="option-items">
              	<a href="<?php echo OSEWPADMINURL.'/options-general.php?page=ose_wp_firewall_conf';?>" >
	              <img src="<?php echo OSEFWURL.'/assets/images/firewall.png'?>" alt="<?php _e(OSE_WORDPRESS_FIREWALL_SETTING, 'ose_wordpress_firwall'); ?>"><br/> 
	              <?php _e(OSE_WORDPRESS_FIREWALL_SETTING, 'ose_wordpress_firwall'); ?>
	            </a> 
              </div>
            </li>
            <li id="vsscan">
              <div class="option-items">
                <a href="<?php echo OSEWPADMINURL.'/options-general.php?page=ose_wp_firewall_avscan';?>" >
	              <img src="<?php echo OSEFWURL.'/assets/images/scan.png'?>" alt="<?php _e(OSE_VIRUS_SCAN, 'ose_wordpress_firwall'); ?>"><br/>
	              <?php _e(OSE_VIRUS_SCAN, 'ose_wordpress_firwall'); ?>
	            </a>    
	          </div>    
            </li>
            <li id="vsscanconf">
              <div class="option-items">
	            <a href="<?php echo OSEWPADMINURL.'/options-general.php?page=ose_wp_firewall_avconf';?>" >
	              <img src="<?php echo OSEFWURL.'/assets/images/setting.png'?>" alt="<?php _e(OSE_WORDPRESS_VIRUSSCAN_CONFIG, 'ose_wordpress_firwall'); ?>"><br/>
	              <?php _e(OSE_WORDPRESS_VIRUSSCAN_CONFIG, 'ose_wordpress_firwall'); ?>
	            </a>  
	          </div>    
            </li>
          </ul>
    </section>
	<section style="" class="compatibility">
          <h2><?php _e(COMPATIBILITY, 'ose_wordpress_firwall'); ?></h2>
          <ol class="browsers">
            <li id="chrome" class="browser">
              <p>Google Chrome 12+</p>
            </li>
            <li id="firefox" class="browser">
              <p>Firefox 5+</p>
            </li>
            <li id="safari" class="browser">
              <p>Safari 5+</p>
            </li>
            <li id="opera" class="browser">
              <p>Opera 11+</p>
            </li>
            <li id="ie" class="browser">
              <p>Internet Explorer 9+</p>
            </li>
          </ol>
    </section>
    <section>
     	<h2><?php _e(OSE_FOLLOWUS, 'ose_wordpress_firwall'); ?></h2>
	    <div id="keepupdated">
				<div class="mod-ose_social">
					<div class="ose_social_icons">
						<div class="socialicon"><a href="http://www.facebook.com/osexcellence" target="_blank"><img src="<?php echo OSEFWURL.'/assets/images/ose_social_fb.png'?>"></a></div>
						<div class="socialicon"><a href="https://twitter.com/#!/osexcellence" target="_blank"><img src="<?php echo OSEFWURL.'/assets/images/ose_social_tw.png'?>"></a></div>
						<div class="socialicon"><a href="http://www.linkedin.com/in/osexcellence" target="_blank"><img src="<?php echo OSEFWURL.'/assets/images/ose_social_in.png'?>"></a></div>
					</div>
				</div>
			</div>
    </section>
	</div>
</div>