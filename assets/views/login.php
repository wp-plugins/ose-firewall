<?php
oseFirewall::checkSubscription ();
$this->model->getNounce ();
$urls = oseFirewall::getDashboardURLs ();
?>
<div id="oseappcontainer">
	<div class="container">
	<?php
	$this->model->showLogo ();
	$this->model->showHeader ();
	?>
	<div class="row">
			<?php 
	  			if (OSE_CMS =='joomla')
	  			{
	  				oseFirewall::callLibClass('audit', 'audit');
	  				$audit = new oseFirewallAudit ();
	  				$plugin = $audit->isPluginEnabled ('plugin', 'centrora', 'system');
	  				if (empty($plugin) || $plugin->enabled == false)
	  				{
	  					$action = (!empty($plugin))?'<button class="btn btn-danger btn-xs fx-button" onClick ="location.href=\'index.php?option=com_plugins&task=plugin.edit&extension_id='.$plugin->extension_id.'\'" >Fix It</button>':'';
	  				}
	  				if (!empty($action))
	  				{	
	  		?>
	  		<div class="col-md-12">
		  		<div class="alert alert-danger fade in">
	                 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	                 	<?php 
	                 		echo '<span class="label label-warning">Warning</span> '.oLang::_get('SYSTEM_PLUGIN_DISABLED').$action;
	                 	?>
	            </div>
            </div>
            <?php 
	  				}
	  			}
            ?>
            <div class="col-md-12">
					<div class="bs-component">
						<div class="panel panel-teal">
							<div class="panel-heading">
								<h3 class="panel-title"><?php oLang::_('SUBSCRIPTION ACTIVATION'); ?></h3>
							</div>
							<div class="panel-controls-buttons">
								 <button onclick="redirectTut('http://www.centrora.com/store/subscription-packages/');" type="button" class="btn btn-primary btn-sm mr5 mb10">Subscribe To A Plan Now</button>
								 <button onclick="redirectTut('http://www.centrora.com/store/index.php?route=affiliate/login');" type="button" class="btn btn-yellow btn-sm mr5 mb10">Get Your Premium Service For FREE</button>
	                             <button class="btn btn-danger btn-sm mr5 mb10" type="button" onClick="redirectTut('https://www.centrora.com/store/activating-premium-service');"><?php oLang::_('TUTORIAL'); ?></button>
	                        </div>
	                        <div class ="row">
	                        	<div class='col-md-11 col-md-offset-1'>
							  		<ul class='nav nav-wizard'>
									  <li class='active'><a href='#step1' data-toggle="tab">Step 1 - Create an Account</a></li>
									  <li><a href='#step2' data-toggle="tab">Step 2 - Place an order</a></li>
									  <li><a href='#step3' data-toggle="tab">Step 3 - Activate the subscription</a></li>
									</ul>
									
									<div id="myTabContent" class="tab-content">
										<div class="tab-pane fade active in" id="step1">
											<div class="col-md-2">
												<img src="//dfsm9194vna0o.cloudfront.net/617220-0-moneybacklogo.png" alt="100% Satisfaction Guarantee">
											</div>
											<div class="col-md-10">
												<p style="padding-top:10px;">Simply create an account by using the form on the right hand side below, or if you have an account in Centrora already, simply sign in by using the form on the left hand side below.
												<br/>We offer 60 days 100% Satisfaction Guarantee, if you are not satisfied, we issue full refund to you without asking a question.</p>
											</div>
										</div>
										<div class="tab-pane fade " id="step2">
											<div class="col-md-2">
												<img src="//dfsm9194vna0o.cloudfront.net/617220-0-moneybacklogo.png" alt="100% Satisfaction Guarantee">
											</div>
											<div class="col-md-10">
											<p style="padding-top:10px;"><img src="<?php echo OSE_FWURL.'/public/images/subscribe_img.png';?>" ><br/>
											Next, click the subscribe button to place an order to a subscrption plan. Once the order is placed, pay your subscription through Paypal or Credit Card. Once payments are made, you will see a subscription is active in the subscriptions table.</p>
											</div>
										</div>
										<div class="tab-pane fade " id="step3">
											<div class="col-md-2">
												<img src="//dfsm9194vna0o.cloudfront.net/617220-0-moneybacklogo.png" alt="100% Satisfaction Guarantee">
											</div>
											<div class="col-md-10">
												<p style="padding-top:10px;"><img src="<?php echo OSE_FWURL.'/public/images/subscribe_img2.png';?>" ><br/>
												Final step: click the link subscription button to activate the subscription for this website.</p>
											</div>	
										</div>
									</div>
								</div>
								
								
							</div>
							<div class="panel-body">
							 <div class="col-md-6 white-bg login-form">
								<div class="panel panel-primary">
								<div class="panel-heading">
									<p>
										<br/>If you have an account already, please enter your <code>Centrora</code> or <code>OSE</code>	Account Information
									</p>
								</div>
								<div class="panel-body">
								<form class="form-horizontal group-border stripped" role="form" id='login-form'>
									<div class="form-group">
										<label for="website" class="col-sm-2 control-label">Website</label>
										<div class="col-sm-10">
											<select class="form-control" name='website' id='website' >
												<option value="centrora">Centrora</option>
												<option value="ose">Open Source Excellence [OSE]</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="username" class="col-sm-2 control-label">Email</label>
										<div class="col-sm-10">
											<input type="textfield" class="form-control" id="email"  name="email" placeholder="Email">
										</div>
									</div>
									<div class="form-group">
										<label for="password" class="col-sm-2 control-label">Password</label>
										<div class="col-sm-10">
											<input type="password" class="form-control" id="password" name="password" placeholder="Password">
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<button type="submit" class="btn btn-default">Sign in</button>
										</div>
									</div>
									<input type="hidden" name="option" value="com_ose_firewall"> 
									<input type="hidden" name="controller" value="login"> 
								    <input type="hidden" name="action" value="validate">
								    <input type="hidden" name="task" value="validate">
								    <?php echo $this->model->getToken();?>
								</form>
								</div>
							</div>
							</div>
							
							
							<div class="col-md-6 white-bg">
								<div class="panel panel-primary">
								<div class="panel-heading">
									<p>
										<br/>If you don't have an account yet, please use the following form to create an account.
									</p>
								</div>
								<div class="panel-body">
								
								<form id = 'new-account-form' class="form-horizontal group-border stripped" role="form">
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('FIRSTNAME');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="firstname" value="" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('LASTNAME');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="lastname" value="" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('EMAIL');?></label>
										<div class="col-sm-8">
				                               <input type="text" name="email" value="" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('PASSWORD');?></label>
										<div class="col-sm-8">
				                               <input type="password" name="password" id="password" value="" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="pageTitle" class="col-sm-4 control-label"><?php oLang::_('PASSWORD_CONFIRM');?></label>
										<div class="col-sm-8">
				                               <input type="password" name="password2" id="password2" value="" class="form-control">
										</div>
									</div>
										<input type="hidden" name="option" value="com_ose_firewall"> 
										<input type="hidden" name="controller" value="login"> 
										<input type="hidden" name="action" value="createaccount">
										<input type="hidden" name="task" value="createaccount">
										<?php echo $this->model->getToken();?>
									<div class="form-group">
										<div class="col-sm-offset-10">
											<button type="submit" class="btn btn-default" id='save-button'><?php oLang::_('CREATE');?></button>
										</div>
									</div>
								</form>
								</div>
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
// \PHPBenchmark\Monitor::instance()->snapshot('Finish loading Centrora');
?>