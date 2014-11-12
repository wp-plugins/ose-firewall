var controller = "audit";

jQuery(document).ready(function($){
	$('#dbinstall-form').submit(function() {
		showLoading ();
		$('#message-box').html('Database installer preparing in progress');
		createTables(0);
	    return false; 
	});
	
	$('#dbuninstall-form').submit(function() {
		showLoading ();
		$('#message-box').html('Database installer preparing in progress');
		uninstallDB();
	    return false; 
	});
	
});

function createTables (step) {
jQuery(document).ready(function($){
	$.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
	    data: {
	    		option : option, 
	    		controller:controller,
	    		action:'createTables',
	    		task:'createTables',
	    		step:step,
	    		centnounce:$('#centnounce').val()
	    },
        success: function(data)
        {
        	if (data.status=='Completed')
			{
        		 hideLoading ();
        		 location.reload(); 
			}
        	else
        	{
        		if (data.cont == 1)
    			{	
    				$('#message-box').html(data.result);
    				createTables (data.step);
    			}
        	}
        }
      });
	});
}

function uninstallDB () {
	jQuery(document).ready(function($){
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:'uninstallTables',
		    		task:'uninstallTables',
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	        	$('#message-box2').html(data.result);
	        	if (data.success==true)
				{
	        		 hideLoading ();
				}
	        }
	      });
		});
}
