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
						<div class="panel-heading">	</div>
						<div class="panel-body">
							<div class="alert alert-info fade in">
								<div class="bg-primary alert-icon">
								     <i class="glyphicon glyphicon-info-sign s24"></i>
								</div>
								<b>Activation Codes:</b>
								<p class="text-default">
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