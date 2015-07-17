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
					<div class="panel panel-primary plain">
						<!-- Start .panel -->
						<div class="panel-heading white-bg"></div>
						<div class="panel-controls"></div>
						<div class="panel-controls-buttons">
                            <button onclick="activateCode();" type="button" class="btn btn-sm mr5 mb10"><i class="text-danger glyphicon glyphicon-flash"></i><?php oLang::_('ENTER_ACTIVATION_CODE'); ?></button>
							<button onclick="redirectTut('<?php $this->model->goSubscribeUrl(); ?>');" type="button" class="btn btn-sm mr5 mb10"><i class="text-primary glyphicon glyphicon-shopping-cart"></i> Subscribe To A Plan Now</button>
                            <button onclick="redirectTut('http://www.centrora.com/premium-service/');" type="button" class="btn btn-sm mr5 mb10"><i class="text-yellow glyphicon glyphicon-info-sign"></i> More about premium service</button>
	                        <button class="btn btn-sm mr5 mb10" type="button" onClick="redirectTut('https://www.centrora.com/store/activating-premium-service');"><i class="text-danger glyphicon glyphicon-book"></i> <?php oLang::_('HOW_TO_ACTIVATE'); ?></button>
	                        <button class="btn btn-sm mr5 mb10" type="button" onClick="centLogout();"><i class="text-yellow glyphicon glyphicon-log-out"></i> <?php oLang::_('LOGOUT'); ?></button>
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

<!-- Form Modal -->
<div class="modal fade" id="activationFormModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel2"><?php oLang::_('ENTER_ACTIVATION_CODE'); ?></h4>
			</div>
			<div class="modal-body">
				<form id='activation-form' class="form-horizontal group-border stripped" role="form">
					<div class="form-group">
						<label for="pageTitle" class="col-sm-6 control-label"><?php oLang::_('ACTIVATION_CODE');?></label>
						<div class="col-sm-6">
							<input type="text" name="code" value="" class="form-control">
						</div>
					</div>
					<input type="hidden" name="option" value="com_ose_firewall">
					<input type="hidden" name="controller" value="subscription"> 
					<input type="hidden" name="action" value="activateCode">
					<input type="hidden" name="task" value="activateCode">
					<div class="form-group">
						<div class="col-sm-offset-10">
							<button type="submit" class="btn btn-default" id='save-button'><?php oLang::_('ACTIVATE');?></button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /.modal -->

<!-- Subscription Form Modal -->
<div class="modal fade" id="subscriptionFormModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel2"><?php oLang::_('CHOOSE_A_PLAN'); ?></h4>
			</div>
			<div class="modal-body">
				<form id='subscription-form' class="form-horizontal group-border stripped" role="form">
					<div class="form-group">
						<label for="subscriptionPlan" class="col-sm-4 control-label"><?php oLang::_('SUBSCRIPTION_PLAN');?></label>
						<div class="col-sm-8">
							<?php 
								$plans = $this->model->getAllSubscriptionPlansHTML ();
								echo $plans;  
							?>
						</div>
					</div>
					<div id="address-group">
						<div class="form-group">
							<label for="payment_method" class="col-sm-4 control-label"><?php oLang::_('PAYMENT_METHOD');?></label>
							<div class="col-sm-8">
								<i class="fa im-paypal">&nbsp;</i>Paypal <input type="radio" name="payment_method" id="payment_method" value="paypal" checked=true > &nbsp; 
								<!-- 
									<i class="fa fa-credit-card">&nbsp;</i>Credit Card <input type="radio" name="payment_method" id="payment_method" value="creditcard">
								 -->
							</div>
						</div>
						<div class="form-group">
							<label for="firstname_field" class="col-sm-4 control-label"><?php oLang::_('FIRST_NAME');?></label>
							<div class="col-sm-8">
								<div id='firstname_field'></div>
							</div>
						</div>
						<div class="form-group">
							<label for="lastname_field" class="col-sm-4 control-label"><?php oLang::_('LAST_NAME');?></label>
							<div class="col-sm-8">
								<div id='lastname_field'></div>
							</div>
						</div>
						<div class="form-group">
							<label for="country_field" class="col-sm-4 control-label"><?php oLang::_('COUNTRY');?></label>
							<div class="col-sm-8">
								<div id='country_field'></div>
							</div>
						</div>
					</div>
					<input type="hidden" name="option" value="com_ose_firewall">
					<input type="hidden" name="controller" value="subscription"> 
					<input type="hidden" name="action" value="addOrder">
					<input type="hidden" name="task" value="addOrder">
					<div class="form-group">
						<div class="col-sm-offset-9" id ="next-button">
							<button type="submit" class="btn btn-default" id='save-button'><?php oLang::_('O_NEXT');?></button>
						</div>
					</div>
				</form>
				<div class="row">
						<div class="col-sm-12">
							<div id="orderInfo"></div>
						</div>
				</div>	
			</div>
		</div>
	</div>
</div>
<!-- /.modal -->