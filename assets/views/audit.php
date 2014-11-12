<?php 
oseFirewall::checkDBReady ();
$urls = oseFirewall::getDashboardURLs();
$confArray = $this->model->getConfiguration('scan');
$seoConfArray = $this->model->getConfiguration('seo');
?>
<div id = "oseappcontainer" >
  <div class="container">
	<?php 
		$this ->model->showLogo ();
		$this ->model->showHeader ();
	?>
	<!-- Row Start -->
	<div class="row">
	 <div class="col-md-12">
	  <div class="bs-component">
	  <!-- Panels Start -->
	  		<div class="panel panel-teal">
			  <div class="panel-heading">
			    <h3 class="panel-title"><?php echo SECURITY_CONFIG_AUDIT; ?></h3>
			  </div>
			  <div class="panel-body">
				  <ul class="list-group">
				    <?php 
		           		$this ->model->showStatus (); 
		           ?>
		           </ul>
			  </div>
			</div>
		    <div class="panel panel-teal">
			  <div class="panel-heading">
			    <h3 class="panel-title"><?php echo SYSTEM_SECURITY_AUDIT; ?></h3>
			  </div>
			  <div class="panel-body">
			  	<ul class="list-group">
				    <?php 
		           		$this ->model->showSytemStatus (); 
		           ?>
	           	</ul>
			  </div>
			</div>
			<!-- Panels Ends -->
	    </div>	
	  </div>
    </div>
 <!-- Row Ends --> 
 </div>
</div>
<div id='fb-root'></div>
<?php 
//\PHPBenchmark\Monitor::instance()->snapshot('Finish loading Centrora');
?>

<?php 
include_once(dirname(__FILE__).'/scanconfig.php');
include_once(dirname(__FILE__).'/adminform.php');
include_once(dirname(__FILE__).'/phpconfig.php');
?>