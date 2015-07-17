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
    $numCol = ($hasOEMCustomer == false) ? 12 : 12;
		echo 'col-md-'.$numCol;
	?>">
		<div class="row">
			<div class="col-md-6">
			  <div class="bs-component">
				    <div class="panel panel-teal">
					  <div class="panel-heading">
					    <h3 class="panel-title"><?php oLang::_('OVERVIEW_COUNTRY_MAP'); ?></h3>
					  </div>
					  <div class="panel-body">
					  	<div id ="world-map" style="width: 100%; height: 280px"></div>
					  </div>
					</div>
			    </div>	
			</div>
            <div class="col-md-6">
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
		</div>
		<div class="row">	
            <div id="ipmange-speech-bubble" class="col-md-6">
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
            <div class="col-md-6">
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
          </div>
          <div class="row">  
            <div class="col-md-6">
                <div class="bs-component">
                    <div class="panel panel-teal">
                        <div class="panel-heading">
                            <h3 class="panel-title"><a
                                    href="<?php $this->model->getPageUrl('backup'); ?>"><?php oLang::_('RECENT_BACKUP'); ?></a>
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
	</div>
 </div>
</div>
<div id='fb-root'></div>
<?php 
//\PHPBenchmark\Monitor::instance()->snapshot('Finish loading Centrora');
?>