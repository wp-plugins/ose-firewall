<div id="container">
	<div id = "stage">
	<div id="header">
		<img src="<?php echo OSEFWURL.'/assets/images/setting.png'?>" alt="<?php _e(OSE_WORDPRESS_VIRUSSCAN_CONFIG, 'ose_wordpress_firewall'); ?>">
		<h1> <?php _e(OSE_VIRUS_SCAN, 'ose_wordpress_firewall'); ?></h1>
		<p> <?php _e(OSE_VIRUS_SCAN_DESC, 'ose_wp_firewall'); ?></p>
	</div>	
	<section style="" class="compatibility">
          <h2><?php _e(COMPATIBILITY, 'ose_wordpress_firewall'); ?></h2>
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
	
	<section class="intro" style=""> 
			<div class="consoleHead">
				<h2><?php _e(OSE_SCAN_SUMMARY, 'ose_wordpress_firewall'); ?></h2>
			</div>
			<div class="consoleInner" id="consoleSummary"></div>
			
			<div class="consoleHead" style="margin-top: 20px;">
				<h2><?php _e(OSE_SCAN_ACTIVITY, 'ose_wordpress_firewall'); ?></h2>
			</div>
			<div class="consoleInner" id="consoleActivity"></div>
	
	</section>
	
	<section class="work">
          <div id="progress_bar" class="ui-progress-bar ui-container transition">
		            <div style="width: 100%;" id="ui-pbar">
		              <span style="display: none;" class="ui-label">Completed</span>
		            </div>
		  </div>
		  <div class="osefirewallScanButton">
			<table style="width: 800px;padding: 10px;border-collapse: collapse;">
			<tr>
				<td>
					<input type="button" value=" <?php _e(START_DB_INIT, 'ose_wordpress_firewall'); ?>" id="wfStartScanButton1" class="btn fork" onclick="osefirewallAdmin.startScan(1);" />
				</td>
				<td>
					<input type="button" value=" <?php _e(START_NEW_VIRUSSCAN, 'ose_wordpress_firewall'); ?>" id="wfStartScanButton2" class="btn download" onclick="osefirewallAdmin.startvsScan(1);" />
				</td>
				<td>
					<input type="button" value=" <?php _e(CONT_VIRUSSCAN, 'ose_wordpress_firewall'); ?>" id="wfStartScanButton3" class="btn download" onclick="osefirewallAdmin.convsscan();" />
				</td>
				<td>
					<input type="button" value=" <?php _e(STOP_DB_INIT, 'ose_wordpress_firewall'); ?>" id="wfStartScanButton4" class="btn fork" onclick="osefirewallAdmin.stopAjax();" />
				</td>
			</tr>
			</table>
		</div>
    </section>
    
    <section style="" class="compatibility">
          <h2><?php _e(NEED_HELP_CLEANING, 'ose_wordpress_firewall'); ?></h2>
          <div>
          <?php _e(NEED_HELP_CLEANING_DESC, 'ose_wordpress_firewall'); ?>
          </div>
    </section>
    
	</div>
</div>