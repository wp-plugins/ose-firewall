<?php
oseFirewall::checkDBReady ();
oseFirewall::checkWebkey();
$this->model->getNounce ();
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
                                    <h4 class="panel-title">My Subscriptions</h4>
                                    
                                </div>
                                <div class="panel-controls"></div>
                                <div class="panel-controls-buttons">
                                <button onclick="redirectTut('http://www.centrora.com/store/index.php?route=affiliate/login');" type="button" class="btn btn-yellow btn-sm mr5 mb10">Get Your Premium Service For FREE</button>
                             <button class="btn btn-danger btn-sm mr5 mb10" type="button" onClick="redirectTut('https://www.centrora.com/store/activating-premium-service');"><?php oLang::_('TUTORIAL'); ?></button>
                                </div>
                                <div class="panel-body">
                                    <table class="table display" id="subscriptionTable">
                                        <thead>
                                            <tr>
												<th><?php oLang::_('O_RECURRING_ID'); ?></th>
												<th><?php oLang::_('O_CREATED'); ?></th>
												<th><?php oLang::_('O_PRODUCT'); ?></th>
												<th><?php oLang::_('O_PROFILE_ID'); ?></th>
												<th><?php oLang::_('O_REMAINING'); ?></th>
												<th><?php oLang::_('O_STATUS'); ?></th>
												<th><?php oLang::_('O_VIEWDETAIL'); ?></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th><?php oLang::_('O_RECURRING_ID'); ?></th>
												<th><?php oLang::_('O_CREATED'); ?></th>
												<th><?php oLang::_('O_PRODUCT'); ?></th>
												<th><?php oLang::_('O_PROFILE_ID'); ?></th>
												<th><?php oLang::_('O_REMAINING'); ?></th>
												<th><?php oLang::_('O_STATUS'); ?></th>
												<th><?php oLang::_('O_VIEWDETAIL'); ?></th>
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