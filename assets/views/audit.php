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
	  		<?php 
	  			if ($this->model->affiliateAccountExists()==false)
	  			{
	  		?>
	  		<!-- Panels Start -->
	  		<div class="panel panel-teal">
			  <div class="panel-heading">
			    <h3 class="panel-title"><?php echo AFFILIATE_ACCOUNT; ?></h3>
			  </div>
			  <div class="panel-body">
				  <ul class="list-group">
				    <li class="list-group-item"><span class="label label-warning">Note</span> <b>[Affiliate Tracking] </b>: Don't miss out the chance to earn at least $14,500 for the 1st year and $338,900 in five years with our affiliate program!  <a class="btn btn-danger btn-xs fx-button" data-toggle="modal" data-target="#affiliateFormModal" href="#">Add Tracking Code</a> <a class="btn btn-primary btn-xs fx-button mr5" href="http://www.centrora.com/store/affiliate-program" target="_blank">Read More</a> </li>
		          </ul>
			  </div>
			</div>
			<!-- Panels Ends -->
	  		<?php 		
	  			}	
	  		?>
	  		<!-- Panels Start -->
	  		<div class="panel panel-teal">
			  <div class="panel-heading">
			    <h3 class="panel-title"><?php echo SAFE_BROWSING_CHECKUP; ?></h3>
			  </div>
			  <div class="panel-body">
				  <ul class="list-group">
				    <?php 
		           		$this ->model->showSafeBrowsingBar (); 
		           ?>
		           </ul>
			  </div>
			</div>
			<!-- Panels Ends -->
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
			<!-- Panels Ends -->
			<!-- Panels Start -->
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
include_once(dirname(__FILE__).'/affiliateform.php');
include_once(dirname(__FILE__).'/phpconfig.php');
?>