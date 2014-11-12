<?php 
oseFirewall::checkDBReady ();
$urls = oseFirewall::getDashboardURLs();
?>
<div id = "oseappcontainer" >
  <div class="container">
	<?php 
		$this ->model->showLogo ();
		$this ->model->showHeader ();
	?>
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
		<div class="col-md-6">
		  <div class="bs-component">
			    <div class="panel panel-teal">
				  <div class="panel-heading">
				    <h3 class="panel-title"><?php oLang::_('RECENT_HACKING_INFO'); ?></h3>
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
	</div>

 </div>
</div>
<div id='fb-root'></div>
<?php 
//\PHPBenchmark\Monitor::instance()->snapshot('Finish loading Centrora');
?>