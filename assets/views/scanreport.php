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
						<?php 
						$isOEMCustomer = CentroraOEM::hasOEMCustomer();
						if ($isOEMCustomer  == false) {
							?>
								<div class ="col-md-12">
									<div class="alert alert-dismissable alert-danger">
									  <button type="button" class="close" data-dismiss="alert">×</button>
                                        <?php oLang::_('O_HELP_CLEAN'); ?>
                                        <button class="btn btn-danger"
                                                onClick="window.location='http://www.centrora.com/cleaning/';">Contact
                                            us here
                                        </button>
									</div>
								</div>
							<?php 
						}
						?>
                        <div class="col-lg-12 sortable-layout">
                            <!-- col-lg-12 start here -->
                            <div class="panel panel-primary plain toggle panelClose panelRefresh">
                                <!-- Start .panel -->
                                <div class="panel-heading white-bg">
                                    <h4 class="panel-title"><?php oLang::_('O_SCAN_REPORT_TITLE'); ?></h4>
                                </div>
                                <div class="panel-controls"></div>
                                <div class="panel-controls-buttons">
                                    <button class="btn btn-success btn-sm mr5 mb10" type="button"
                                            onClick="batchbkcl()"><?php oLang::_('O_SCANREPORT_CLEAN'); ?></button>
                                    <button class="btn btn-success btn-sm mr5 mb10" type="button"
                                            onClick="batchquarantine()"><?php oLang::_('O_SCANREPORT_QUARANTINE'); ?></button>
                                    <button class="btn btn-success btn-sm mr5 mb10" type="button"
                                            onClick="batchrs()"><?php oLang::_('O_SCANREPORT_RESTORE'); ?></button>
                                    <button id="delete-button" class="btn btn-danger btn-sm mr5 mb10" type="button"
                                            style="display: none"
                                            onClick="confirmbatchdl()"><?php oLang::_('O_SCANREPORT_DELETE'); ?></button>
                                </div>
                                <div class="panel-body">
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
				$image = OSE_FWURL.'/public/images/screenshot-7.png';
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