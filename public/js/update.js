function updateNumbOfWebsite()
{
	//showLoading();
	jQuery(document).ready(function($){
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : 'com_ose_firewall', 
		    		controller: 'login',
		    		action:'getNumbOfWebsite',
		    		task:'getNumbOfWebsite',
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
		           //hideLoading();
		           $('#numofWebsite').text(data.total);
	        }
	      });
	});
}

jQuery(document).ready(function($){
	updateNumbOfWebsite();
});