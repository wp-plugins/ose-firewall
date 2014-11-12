var controller ='subscription';
		    
function goSubscribe () {
	window.open(
			  'http://www.centrora.com/store/centrora-subscriptions',
			  '_blank' // <- This is what makes it open in a new window.
			);
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
