var controller = "cronjobs";
var option = "com_ose_firewall";

jQuery(document).ready(function($){
	$('#cronjobs-form').submit(function() {
		showLoading ();
    	// submit the form 
        $(this).ajaxSubmit({
        	url:url,
        	type: "POST",
        	success: function(data) { 
        		data = jQuery.parseJSON(data);
        		if (data.success== true)
        		{
        			if (data.status =='Error')
        			{
        				showDialogue (data.message, data.status, 'OK');
        			}
        			else
        			{
        				showLoading (data.message);
            			hideLoading ();
        			}
        		}
        		else
        		{
        			hideLoading ();
        			showDialogue (data.result, data.status, 'OK');
        		}	
            } 
        }); 
        return false; 
    });
});


