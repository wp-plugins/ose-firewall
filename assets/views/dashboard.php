<?php 
oseFirewall::checkDBReady ();
$this->model->getNounce ();
$urls = oseFirewall::getDashboardURLs();
$hasOEMCustomer = CentroraOEM::hasOEMCustomer();
?>
<div id = "oseappcontainer" >
  <div class="container">
	<?php 
		$this ->model->showLogo ();
		$this ->model->showHeader ();
	?>
	<div class="row">
	<div class="<?php
    $numCol = ($hasOEMCustomer == false) ? 9 : 12;
		echo 'col-md-'.$numCol;
	?>">
		<div class="row">
			<div class="col-md-12">
			  <div class="bs-component">
				    <div class="panel panel-teal">
					  <div class="panel-heading">
					    <h3 class="panel-title"><?php oLang::_('OVERVIEW_COUNTRY_MAP'); ?></h3>
					  </div>
					  <div class="panel-body">
					  	<div id ="world-map" style="width: 100%; height: 470px"></div>
					  </div>
					</div>
			    </div>	
			</div>
            <div class="col-md-7">
			  <div class="bs-component">
				    <div class="panel panel-teal">
					  <div class="panel-heading">
					    <h3 class="panel-title"><?php oLang::_('OVERVIEW_TRAFFICS'); ?></h3>
					  </div>
					  <div class="panel-body">
					  	<div id ="traffic-overview" style="width: 100%; height:290px;"></div>
					  </div>
					</div>
			    </div>	
			</div>
            <div id="ipmange-speech-bubble" class="col-md-5">
			  <div class="bs-component">
				    <div class="panel panel-teal">
					  <div class="panel-heading">
                          <h3 class="panel-title"><a
                                  href="<?php $this->model->getPageUrl('ipmanage'); ?>"><?php oLang::_('RECENT_HACKING_INFO'); ?></a>
                          </h3>
					  </div>
					  <div class="panel-body">
					  		<table class="table display" id="IPsTable">
	                            <thead>
	                                   <tr>
	                                    <th><?php oLang::_('O_DATE'); ?></th>
										<th><?php oLang::_('O_START_IP'); ?></th>
										<th><?php oLang::_('O_RISK_SCORE'); ?></th>
										<th><?php oLang::_('O_STATUS'); ?></th>
	                                   </tr>
	                            </thead>
	                        </table>
					  </div>
					</div>
			    </div>	
			</div>
            <div class="col-md-7">
                <div class="bs-component">
                    <div class="panel panel-teal">
                        <div class="panel-heading">
                            <h3 class="panel-title"><a
                                    href="<?php $this->model->getPageUrl('scanResult'); ?>"><?php oLang::_('RECENT_SCANNING_RESULT'); ?></a>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <table class="table display" id="scanRecentResultTable">
                                <thead>
                                <tr>
                                    <th><?php oLang::_('O_FILE_ID'); ?></th>
                                    <th><?php oLang::_('O_FILE_NAME'); ?></th>
                                    <th><?php oLang::_('O_CHECKSTATUS'); ?></th>
                                    <th><?php oLang::_('O_CONFIDENCE'); ?></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="bs-component">
                    <div class="panel panel-teal">
                        <div class="panel-heading">
                            <h3 class="panel-title"><a
                                    href="<?php $this->model->getPageUrl('scanResult'); ?>"><?php oLang::_('RECENT_BACKUP'); ?></a>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <table class="table display" id="backupTable">
                                <thead>
                                <tr>
                                    <th><?php oLang::_('O_BACKUPFILE_ID'); ?></th>
                                    <th><?php oLang::_('O_BACKUPFILE_DATE'); ?></th>
                                    <th><?php oLang::_('O_BACKUPFILE_NAME'); ?></th>
                                    <th><?php oLang::_('O_BACKUPFILE_TYPE'); ?></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	  </div>
	  <?php 
		if ($hasOEMCustomer == false )  {
	  ?>
		  <div class="col-md-3 white-bg fb-left">
              <iframe class="" width="1000px" height="1000px" frameborder="0" name="f89f59a1d1d4b4"
                      allowtransparency="true" scrolling="no" title="fb:like_box Facebook Social Plugin"
                      style="border: medium none; visibility: visible; width: 300px; height: 541px;"
                      src="https://www.facebook.com/plugins/like_box.php?app_id=&channel=http%3A%2F%2Fstatic.ak.facebook.com%2Fconnect%2Fxd_arbiter%2F7r8gQb8MIqE.js%3Fversion%3D41%23cb%3Dfbcbe97fbffaa8%26domain%3Dwww.centrora.com%26origin%3Dhttp%253A%252F%252Fwww.centrora.com%252Ff2a72a2585d4c42%26relation%3Dparent.parent&header=false&href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FOSE-Firewall%2F359461984157157&locale=en_US&sdk=joey&show_border=false&show_faces=true&stream=true"></iframe>
		  </div>
	  <?php 
		}
	  ?>
	</div>	

 </div>
</div>
<div id='fb-root'></div>
<?php 
//\PHPBenchmark\Monitor::instance()->snapshot('Finish loading Centrora');
?>