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
	<div class="row ">
						<div class ="col-md-12">
							<div class="alert alert-dismissable alert-danger">
							  <button type="button" class="close" data-dismiss="alert">×</button>
							  <strong>Need Help Cleaning?</strong> We can help you clean the infected files. <button class="btn btn-danger" onClick ="window.location='http://www.centrora.com/cleaning/';">Contact us here</button>
							</div>
						</div>
                        <div class="col-lg-12 sortable-layout">
                            <!-- col-lg-12 start here -->
                            <div class="panel panel-primary plain toggle panelClose panelRefresh">
                                <!-- Start .panel -->
                                <div class="panel-heading white-bg">
                                    <h4 class="panel-title">IP Table</h4>
                                    
                                </div>
                                <div class="panel-controls"></div>
                                <div class="panel-controls-buttons">
                                	<button class="btn btn-success btn-sm mr5 mb10" type="button" onClick = "showLoading();"><?php oLang::_('ADD_IPS'); ?></button>
                                </div>
                                <div class="panel-body">
                                    <table class="table display" id="scanreportTable">
                                        <thead>
                                            <tr>
                                                <th><?php oLang::_('O_FILE_ID'); ?></th>
								                <th><?php oLang::_('O_FILE_NAME'); ?></th>
								                <th><?php oLang::_('O_PATTERNS'); ?></th>
								                <th><?php oLang::_('O_PATTERN_ID'); ?></th>
								                <th><?php oLang::_('O_CONFIDENCE'); ?></th>
								                <th><?php oLang::_('VIEW'); ?></th>
												<th><input type="checkbox" name="checkedAll" id="checkedAll"></input></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th><?php oLang::_('O_FILE_ID'); ?></th>
								                <th><?php oLang::_('O_FILE_NAME'); ?></th>
								                <th><?php oLang::_('O_PATTERNS'); ?></th>
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
		$this ->model->showHeader ();
	?>
	<div class="row">
		<div class="panel panel-primary">
		<div class="col-md-9">
			<img src ="<?php echo OSE_FWURL.'/public/images/screenshot-7.png'; ?>" />
		</div>
		<?php include_once dirname(__FILE__).'/calltoaction.php';?>
		</div>
	</div>
  </div>
</div>
<?php 
}
?>