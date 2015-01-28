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
							 <button onclick="redirectTut('http://www.centrora.com/store/index.php?route=affiliate/login');" type="button" class="btn btn-yellow btn-sm mr5 mb10">Get Your Premium Service For FREE</button>
                             <button class="btn btn-danger btn-sm mr5 mb10" type="button" onClick="redirectTut('https://www.centrora.com/store/activating-premium-service');"><?php oLang::_('TUTORIAL'); ?></button>
                             <button data-target="#accountFormModal" data-toggle="modal" class="btn btn-success btn-sm mr5 mb10" type="button"><?php oLang::_('CREATE_ACCOUNT'); ?></button>
                        </div>
						<div class="panel-body">
							<div class="alert alert-info">
								<p>
									Please enter your <code>Centrora</code> or <code>OSE</code>	Account Information
								</p>
							</div>
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
			</div>
		</div>

	</div>
</div>
<div id='fb-root'></div>
<?php
// \PHPBenchmark\Monitor::instance()->snapshot('Finish loading Centrora');
?>
<?php 
include_once(dirname(__FILE__).'/account.php');
?>