var controller ='subscription';
		    
function goSubscribe () {
 jQuery(document).ready(function($){	
	$.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
	    data: {
	    		option : option, 
	    		controller:'audit',
	    		action:'getTrackingCode',
	    		task:'getTrackingCode',
	    		centnounce:$('#centnounce').val()
	    },
        success: function(data)
        { 
        	var redirect = '';
        	if (data.product =='st')
        	{
        		redirect = 'http://www.centrora.com/store/centrora-subscriptions/suite-annual';
        	}
        	else
        	{
        		redirect = 'http://www.centrora.com/store/centrora-subscriptions';
        	}
        	if (data.trackingCode!='' && data.trackingCode!=null)
        	{
        		redirect += '?tracking='+data.trackingCode;
        	}
        	window.open(
        		  redirect,'_blank' // <- This is what makes it open in a new window.
        	);
        }
	});  
 });  
}

function updateProfileID (profileID, profileStatus) {
 jQuery(document).ready(function($){
	$.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
	    data: {
	    		option : option, 
	    		controller:controller,
	    		action:'updateProfileID',
	    		task:'updateProfileID',
	    		profileID:profileID,
	    		profileStatus: profileStatus,
	    		centnounce:$('#centnounce').val()
	    },
        success: function(data)
        { }
	});  
   });
}

function centLogout () {
	jQuery(document).ready(function($){ 
		showLoading ();
		$.ajax({
		        type: "POST",
		        url: url,
		        dataType: 'json',
			    data: {
			    		option : option, 
			    		controller:controller,
			    		action:'logout',
			    		task:'logout',
			    		centnounce:$('#centnounce').val()
			    },
		        success: function(data)
		        {
		           hideLoading ();
		           showDialogue (data.message, data.status, 'OK');
		           location.reload(); 
		        }
		});
	});
}

function linkSub(profileID) {
	jQuery(document).ready(function($){ 
		showLoading ();
		$.ajax({
		        type: "POST",
		        url: url,
		        dataType: 'json',
			    data: {
			    		option : option, 
			    		controller:controller,
			    		action:'linkSubscription',
			    		task:'linkSubscription',
			    		profileID:profileID,
			    		centnounce:$('#centnounce').val()
			    },
		        success: function(data)
		        {
		           hideLoading ();
		           showDialogue (data.message, data.status, 'OK');
		           $('#subscriptionTable').dataTable().api().ajax.reload();
		           if (data.profileID!='undefined' && data.profileID!='')
		           {
		        	   updateProfileID (data.profileID, data.profileStatus);
		           }
		        }
		});
	});
}

jQuery(document).ready(function($){
	var subscriptionTable = $('#subscriptionTable').dataTable( {
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function ( d ) {
                d.option = option;
                d.controller = controller;
                d.action = 'getSubscription';
                d.task = 'getSubscription';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [
                { "data": "recurringID"},
                { "data": "created" },
                { "data": "product" },
                { "data": "profileID" },
                { "data": "quantity" },
                { "data": "status" },
                { "data": "action" }
        ]
    });
});
