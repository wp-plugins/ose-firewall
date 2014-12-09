<?php
$this->model->getNounce ();
$activation_code = $this->model->getActivationCode();
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
							<h3 class="panel-title"><?php oLang::_('ACTIVATION_CODES'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="alert alert-info">
								<p>
									<?php echo $activation_code; ?>
								</p>
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