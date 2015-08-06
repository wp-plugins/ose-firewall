var controller ='subscription';
		    
function activateCode () {
	jQuery(document).ready(function($){	
		$('#activationFormModal').modal();
	});
}

jQuery(document).ready(function($){	
	$("#activation-form").submit(function() {
		showLoading();
		var data = $("#activation-form").serialize();
		data += '&centnounce='+$('#centnounce').val();
        $.ajax({
               type: "POST",
               url: url,
               data: data, // serializes the form's elements.
               success: function(data)
               {
            	   data = jQuery.parseJSON(data);
            	   hideLoading();
            	   $('#activationFormModal').modal('hide');
                   showDialogue(data.message, data.status, O_OK);
               }
             });
        return false; // avoid to execute the actual submit of the form.
    });
});

function getPaymentAddress () {
	 showLoading ('Please wait, generating a new order...');
	 jQuery(document).ready(function($){
			$.ajax({
		        type: "POST",
		        url: url,
		        dataType: 'json',
			    data: {
			    		option : option, 
			    		controller:'subscription',
			    		action:'getPaymentAddress',
			    		task:'getPaymentAddress',
			    		centnounce:$('#centnounce').val()
			    },
		        success: function(data)
		        { 
		        	if (data.status =='Error')
		        	{
		        		hideLoading();
                        showDialogue(data.message, data.status, O_OK);
		        	}
		        	else
		        	{
		        		var html = '<select id="country_id" name="country_id">';
			    		for (index = 0, len = data.list.length; index < len; ++index)
			    		{
			    			var selected = '';
			    			if (data.selected == data.list[index].country_id) {
			    				var selected = ' selected ';
			    			}
			    			html += '<option value="'+data.list[index].country_id+'" '+selected+'>'+data.list[index].name+'</option>';
			    		}
			    	 	html += '</select>';
			    		$('#country_field').html(html);
			    		
			    		var firstname = '<input id="firstname" name="firstname" value="'+data.firstname+'" />';
			    		var lastname = '<input id="lastname" name="lastname" value="'+data.lastname+'" />';
			    		$('#firstname_field').html(firstname);
			    		$('#lastname_field').html(lastname);
			    		
			    		$('#subscriptionFormModal').modal();
			    		hideLoading();
		        	}
		        	
		        },
		        failure: function (data)
		        {}
			}); 
	 });	
}
function goSubscribe() {
    window.open('http://www.centrora.com/store/centrora-subscriptions', '_blank');
}
function goSubscribe3 () {
	 jQuery(document).ready(function($){	
     	getPaymentAddress ();
 		$('#subscription-form').on("submit", function () {
			showLoading();
			var data = $("#subscription-form").serialize();
			data += '&centnounce='+$('#centnounce').val();
	        $.ajax({
	               type: "POST",
	               url: url,
	               data: data, // serializes the form's elements.
	               success: function(data)
	               {
	            	   $('#address-group').hide();
	            	   data = jQuery.parseJSON(data);
	            	   hideLoading();
	            	   $('#next-button').hide();
	            	   var button = data.paymentlink;
                       $('#orderInfo').html('<div class="orderInfo">' + O_ORDER_NOTICE + '<br/>' + data.orderInfo + '<br/>' + button + '</div>');
	               }
	             });
			return false;
		});
		return false;
	 });  
}

function redirectLink () {
	showLoading ('Please wait, redirecting to Paypal...');
	 jQuery(document).ready(function($){
		 	$('#payment-form').submit();
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
                    showDialogue(data.message, data.status, O_OK);
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
                    showDialogue(data.message, data.status, O_OK);
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
