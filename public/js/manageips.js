var controller = "manageips";

jQuery(document).ready(function($){
    var manageIPsDataTable = $('#manageIPsTable').dataTable( {
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function ( d ) {
                d.option = option;
                d.controller = controller;
                d.action = 'getACLIPMap';
                d.task = 'getACLIPMap';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [
                { "data": "country_code"},
                { "data": "id" },
                { "data": "datetime" },
                { "data": "name" },
                { "data": "score" },
                { "data": "ip32_start" },
                { "data": "ip32_end" },
                { "data": "status" },
                { "data": "visits" },
                { "data": "view" },
                { "data": "checkbox", sortable: false }
        ]
    });
    $('#manageIPsTable tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    });
    
    $('#checkedAll').on('click', function() {
    	$('#manageIPsTable').dataTable().api().rows()
        .nodes()
        .to$()
        .toggleClass('selected');
    })
    
    var statusFilter = $('<label>Status: <select name="statusFilter" id="statusFilter"><option value="0"></option><option value="1">Blacklisted</option><option value="2">Monitored</option><option value="3">Whitelisted</option></select></label>');
    statusFilter.appendTo($("#manageIPsTable_filter")).on( 'change', function () {
        var val = $('#statusFilter');
         manageIPsDataTable.api().column(7)
            .search( val.val(), false, false )
            .draw();
    });
    $('#ip_start').ipAddress();
    $('#ip_end').ipAddress();
    $("#add-ip-form").submit(function() {
        $.ajax({
               type: "POST",
               url: url,
               data: $("#add-ip-form").serialize(), // serializes the form's elements.
               success: function(data)
               {
            	   data = jQuery.parseJSON(data);
            	   $('#addIPModal').modal('hide');
       			   showDialogue (data.result, data.status, 'OK');
           	       $('#manageIPsTable').dataTable().api().ajax.reload();
               }
             });
        return false; // avoid to execute the actual submit of the form.
    });
    var correctFormat = '<br/>Please create the CSV file with the following headers: title, ip_start, ip_end, ip_type, ip_status. <br/><br/> Explanations:<br/><br/>'+
		'<ul>'+
  		'<li>title: the title of the rule for this IP / IP Range<li>'+
  		'<li>ip_start: the start IP in the IP Range<li>'+
  		'<li>ip_end: the end IP in the IP Range<li>'+
  		'<li>ip_type: the type of this record, \'0\' refers to one single IP, whereas \'1\' refers to IP ranges<li>'+
  		'<li>ip_status: the status of the IP, \'1\' for blocked IP, \'3\' for whitelisted IP, \'2\' for monitored IP <li>'+
  		'</ul>';
    $('#import-ip-form').submit(function() {
    	showLoading ();
    	// submit the form 
        $(this).ajaxSubmit({
        	url:url,
        	success: function(data) { 
        		data = jQuery.parseJSON(data);
        		if (data.success== true)
        		{
        			hideLoading ();
        			$('#importModal').modal('hide');
        			$('#manageIPsTable').dataTable().api().ajax.reload();
        			showDialogue (data.result, data.status, 'OK');
        		}
        		else
        		{
        			hideLoading ();
        			showDialogue (data.result+correctFormat, data.status, 'OK');
        		}	
            } 
        }); 
        // return false to prevent normal browser submit and page navigation 
        return false; 
    });
    
    $('#export-ip-form').submit(function() {
    	showLoading ();
    	// submit the form 
        $(this).ajaxSubmit({
        	url:url,
        	success: function(data) { 
        		data = jQuery.parseJSON(data);
        		if (data.success== true)
        		{
        			hideLoading ();
        			$('#exportModal').modal('hide');
        			window.open(data.result,'_blank');
        		}
        		else
        		{
        			hideLoading ();
        			showDialogue (data.result+correctFormat, data.status, 'OK');
        		}	
            } 
        }); 
        return false; 
    });
});

function changeItemStatus(id, status)
{
	AppChangeItemStatus(id, status, '#manageIPsTable', 'changeIPStatus');
}

function changeBatchItemStatus (action) {
	AppChangeBatchItemStatus (action, '#manageIPsTable');
}

function removeItems () {
	AppRemoveItems ('removeips');
}

function removeAllItems () {
	AppRemoveAllItems ('removeAllIPs', '#manageIPsTable');
}

function viewIPdetail(id)
{
	showLoading ();
	jQuery(document).ready(function($){
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:'viewAttack',
		    		task:'viewAttack',
		    		id: id,
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	        	hideLoading ();
	        	showDialogue (data.result, data.status, 'OK', 'detailed-form');
	        }
	      });
	});
}