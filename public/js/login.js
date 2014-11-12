var controller ='login';

function verifyKey (key) {
	jQuery(document).ready(function($){
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:'verifyKey',
		    		task:'verifyKey',
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	           if (data.status =='SUCCESS')
	           {
	        	   updateKey (key, 1);
	        	   location.reload();
	           }
	           hideLoading ();
	           showDialogue (data.message, data.status, 'OK', null);
	        }
	     });
	  });	
}

function updateKey (key, verified) {
	jQuery(document).ready(function($){
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:'updateKey',
		    		task:'updateKey',
		    		key:key,
		    		verified:verified,
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	           if (verified == 0)
	           {
	        	   showLoading(data.result);
	        	   verifyKey (key);
	           }
	           else
	           {
	        	   hideLoading ();
		       }
	        }
	     });
  });	
}
		    		
jQuery(document).ready(function($){
    $('#login-form').submit(function() {
    	showLoading ();
    	// submit the form 
        $(this).ajaxSubmit({
        	url:url,
        	type: "POST",
        	success: function(data) { 
        		data = jQuery.parseJSON(data);
        		if (data.success== true && data.status!='Error' && data.webkey!='')
        		{
        			showLoading(data.message);
        			updateKey (data.webkey, 0);
        		}
        		else
        		{
        			showDialogue (data.message, data.status, 'OK', null);
        			hideLoading ();
        		}	
            } 
        }); 
        // return false to prevent normal browser submit and page navigation 
        return false; 
    });
    
    $('#new-account-form').submit(function() {
    	showLoading ();
    	// submit the form 
        $(this).ajaxSubmit({
        	url:url,
        	type: "POST",
        	success: function(data) { 
        		data = jQuery.parseJSON(data);
        		if (data.success== true)
        		{
        			$('#accountFormModal').modal('hide');
        			showDialogue (data.message, data.status, 'OK', null);
        			hideLoading ();
        		}
        		else
        		{
        			showDialogue (data.result, data.status, 'OK', null);
        			hideLoading ();
        		}	
            }
        }); 
        // return false to prevent normal browser submit and page navigation 
        return false; 
    });
});
