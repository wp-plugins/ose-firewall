<?php
oseFirewall::checkDBReady ();
$this->model->getNounce ();
$confArray = $this->model->getConfiguration('scan');
$seoConfArray = $this->model->getConfiguration('seo');
?>
<div id="oseappcontainer">
	<div class="container">
	<?php
	$this->model->showLogo ();
	$this->model->showHeader ();
	?>
	<div class="content-inner">
	<div class="row ">
                        <div class="col-lg-12 sortable-layout">
                            <!-- col-lg-12 start here -->
                            <div class="panel panel-primary plain toggle panelClose panelRefresh">
                                <!-- Start .panel -->
                                <div class="panel-heading white-bg">
                                    <h4 class="panel-title"><?php oLang::_('O_RULESETS_TABLE_TITLE'); ?><?php echo oseFirewall::isSigUpdated(); ?></h4>
                                </div>
                                <div class="panel-controls">
                                </div>
                                 <div class="panel-controls-buttons">
                                    <button class="btn btn-danger btn-sm mr5 mb10" type="button" onClick="redirectTut('http://www.centrora.com/centrora-joomla-component-tutorial/firewall-settings-3/');"><?php oLang::_('TUTORIAL'); ?></button>
                                	<button data-target="#configModal" onClick="location.href='<?php echo oseFirewall::getConfigurationURL();;?>'" class="btn btn-success btn-sm mr5 mb10" type="button"><?php oLang::_('FIREWALL_CONFIGURATION'); ?></button>
                                </div>
                                <div class="panel-body">
                                    <table class="table display" id="rulesetsTable">
                                        <thead>
                                            <tr>
												<th><?php oLang::_('O_ID'); ?></th>
												<th><?php oLang::_('O_RULE'); ?></th>
												<th><?php oLang::_('O_ATTACKTYPE'); ?></th>
												<th><?php oLang::_('O_STATUS'); ?></th>
												<th></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
												<th><?php oLang::_('O_ID'); ?></th>
												<th><?php oLang::_('O_RULE'); ?></th>
												<th><?php oLang::_('O_ATTACKTYPE'); ?></th>
												<th><?php oLang::_('O_STATUS'); ?></th>
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