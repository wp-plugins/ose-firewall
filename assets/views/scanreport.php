<?php
oseFirewall::checkDBReady ();
$status = oseFirewall::checkSubscriptionStatus (false);
$this->model->getNounce ();
if ($status == true)
{
?>
<div id="oseappcontainer">
	<div class="container">
	<?php
	$this->model->showLogo ();
	$this->model->showHeader ();
	?>
	<div class="content-inner">
        <div class="row">
						<div class="col-lg-12 sortable-layout">
                            <!-- col-lg-12 start here -->
                            <div class="panel panel-primary plain toggle panelClose panelRefresh">
                                <!-- Start .panel -->
                                <div class="panel-heading white-bg"></div>
                                <div class="panel-controls"></div>
                                <div class="panel-body">
                                 <?php 
									$isOEMCustomer = CentroraOEM::hasOEMCustomer();
									if ($isOEMCustomer  == false) {
										?>
										<div class="row">
											<div class ="col-md-12">
												<div class="alert alert-dismissable alert-danger">
													<div class="bg-danger alert-icon">
								                            <i class="glyphicon glyphicon-user s24"></i>
								                      </div>
			                                        <?php oLang::_('O_HELP_CLEAN'); ?>
			                                        <button class="btn btn-danger btn-sm" onClick="window.location='http://www.centrora.com/cleaning/';"><i class="glyphicon glyphicon-phone"></i> Contact us here </button>
												</div>
											</div>
										</div>	
										<?php 
									}
								?>
								<div class="row">
                                  <div class="col-md-12">
                                   <div class="clean-buttons pull-right">
	                                    <button class="btn btn-sm mr5 mb10" type="button" onClick="batchbkcl()">
											<i class="text-success glyphicon glyphicon-erase"></i>
											<?php oLang::_('O_SCANREPORT_CLEAN'); ?>
										</button>
	                                    <button class="btn btn-sm mr5 mb10" type="button" onClick="batchquarantine()">
											<i class="text-primary glyphicon glyphicon-alert"></i>
											<?php oLang::_('O_SCANREPORT_QUARANTINE'); ?>
										</button>
									   <button class="btn btn-sm mr5 mb10" type="button" onClick="batchMarkAsClean()">
										   <i class="text-warning glyphicon glyphicon-check"></i>
										   <?php oLang::_('O_SCANREPORT_MARKASCLEAN'); ?>
									   </button>
	                                    <button class="btn btn-sm mr5 mb10" type="button" onClick="batchrs()">
											<i class="text-success glyphicon glyphicon-retweet"></i>
											<?php oLang::_('O_SCANREPORT_RESTORE'); ?>
										</button>
	                                    <button id="delete-button" class="btn btn-danger btn-sm mr5 mb10" type="button"
												style="display: none" onClick="confirmbatchdl()">
											<i class="text-danger glyphicon glyphicon-trash"></i>
											<?php oLang::_('O_SCANREPORT_DELETE'); ?>
										</button>
                                  </div>
                                 </div>
                                </div>
                                
                                    <table class="table display" id="scanreportTable">
                                        <thead>
                                            <tr>
                                                <th><?php oLang::_('O_FILE_ID'); ?></th>
								                <th><?php oLang::_('O_FILE_NAME'); ?></th>
								                <th><?php oLang::_('O_PATTERNS'); ?></th>
                                                <th><?php oLang::_('O_CHECKSTATUS'); ?></th>
								                <th><?php oLang::_('O_PATTERN_ID'); ?></th>
								                <th><?php oLang::_('O_CONFIDENCE'); ?></th>
								                <th><?php oLang::_('VIEW'); ?></th>
                                                <th><input id='checkbox' type='checkbox'></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th><?php oLang::_('O_FILE_ID'); ?></th>
								                <th><?php oLang::_('O_FILE_NAME'); ?></th>
								                <th><?php oLang::_('O_PATTERNS'); ?></th>
                                                <th><?php oLang::_('O_CHECKSTATUS'); ?></th>
								                <th><?php oLang::_('O_PATTERN_ID'); ?></th>
								                <th><?php oLang::_('O_CONFIDENCE'); ?></th>
								                <th><?php oLang::_('VIEW'); ?></th>
												<th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- End .panel -->
                        </div>
	   </div>
	   <?php 
//			CentroraOEM::showProducts();
	   ?>
	   </div>
	</div>
</div>

<?php 
include_once(dirname(__FILE__).'/filecontent.php');
}
else {
?>
<div id = "oseappcontainer" >
  <div class="container">
	<?php 
		$this ->model->showLogo ();
	?>
	<div class="row">
		<div class="panel panel-primary">
			<?php 
				$image = OSE_FWURL.'/public/images/premium/virusscanreport.png';
				include_once dirname(__FILE__).'/calltoaction.php';
			?>
		</div>
	</div>
  </div>
</div>
<?php 
	$this->model->showFooterJs();
}
?>