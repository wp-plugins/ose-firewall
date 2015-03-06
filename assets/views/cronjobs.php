<?php
oseFirewall::checkDBReady ();
$status = oseFirewall::checkSubscriptionStatus (false);
$this->model->getNounce ();
$urls = oseFirewall::getDashboardURLs ();
$settings = $this->model->getCronSettings ();
if ($status == true)
{
?>
<div id="oseappcontainer">
	<div class="container">
	<?php
	$this->model->showLogo ();
	$this->model->showHeader ();
	?>
	<div class="row">
			<div class="col-md-12">
				<div class="bs-component">
					<div class="panel panel-teal">
						<div class="panel-heading">
							<h3 class="panel-title"><?php oLang::_('CRONJOBS_LONG'); ?></h3>
						</div>
						<form id = 'cronjobs-form' class="form-horizontal group-border stripped" role="form">
							<div class="panel-body">
								<div class="col-md-4">
									<div class="panel panel-warning">
										<div class="panel-heading">
											<h3 class="panel-title"><?php oLang::_('HOURS'); ?></h3>
										</div>
										<div class="panel-body">
											<select class="cron" id="custhours" name="custhours" size="10" >
												<?php 
													for ($i=0; $i<24; $i++) {
														$selected = ($i==$settings['hour'])?" selected ":"";
														echo '<option value="'.$i.'" '.$selected.'>'.$i.':00</option>';
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="panel panel-warning">
										<div class="panel-heading">
											<h3 class="panel-title"><?php oLang::_('WEEKDAYS'); ?></h3>
										</div>
										<div class="panel-body">
											<select class="cron" name="custweekdays[]" size="7" multiple="">
												<option value="0" <?php echo ($settings[0] == true)?" selected ":""; ?>>Sun</option>
												<option value="1" <?php echo ($settings[1] == true)?" selected ":""; ?>>Mon</option>
												<option value="2" <?php echo ($settings[2] == true)?" selected ":""; ?>>Tue</option>
												<option value="3" <?php echo ($settings[3] == true)?" selected ":""; ?>>Wed</option>
												<option value="4" <?php echo ($settings[4] == true)?" selected ":""; ?>>Thu</option>
												<option value="5" <?php echo ($settings[5] == true)?" selected ":""; ?>>Fri</option>
												<option value="6" <?php echo ($settings[6] == true)?" selected ":""; ?>>Sat</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="cron-buttons">
										<button class="btn btn-primary" type="submit">Save Setting</button>
									</div>
								</div>
							</div>
							<input type="hidden" name="option" value="com_ose_firewall">
							<input type="hidden" name="controller" value="cronjobs"> 
							<input type="hidden" name="action" value="saveCronConfig">
							<input type="hidden" name="task" value="saveCronConfig">
					   </form>	
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id='fb-root'></div>
<?php 
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
				$image = OSE_FWURL.'/public/images/screenshot-9.png';
				include_once dirname(__FILE__).'/calltoaction.php';
			?>
		</div>
	</div>
  </div>
</div>
<?php 
	$this->model->showFooterJs();
}
// \PHPBenchmark\Monitor::instance()->snapshot('Finish loading Centrora');
?>