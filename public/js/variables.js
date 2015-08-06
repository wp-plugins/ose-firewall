var url = ajaxurl; 
var controller = "variables";
var option = "com_ose_firewall";

jQuery(document).ready(function($){
    var rulesetsDataTable = $('#variablesTable').dataTable( {
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function ( d ) {
                d.option = option;
                d.controller = controller;
                d.action = 'getVariables';
                d.task = 'getVariables';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [
                { "data": "id", width: '5%'},
                { "data": "keyname", width: '60%'},
                { "data": "status" , width: '10%'},
                { "data": "statusexp" , width: '20%'},
                { "data": "checkbox", sortable: false , width: '5%'}
        ]
    });
    $('#variablesTable tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    });
    $('#checkedAll').on('click', function() {
    	$('#variablesTable').dataTable().api().rows()
        .nodes()
        .to$()
        .toggleClass('selected');
    });
    var statusFilter = $('<label>Status: <select name="statusFilter" id="statusFilter"><option value="-1"></option><option value="1">Active</option><option value="3">InActive</option></select></label>');
    statusFilter.appendTo($("#variablesTable_filter")).on( 'change', function () {
        var val = $('#statusFilter');
         rulesetsDataTable.api().column(3)
            .search( val.val(), false, false )
            .draw();
    });
    $("#add-variable-form").submit(function() {
    	showLoading(O_PLEASE_WAIT);
        $.ajax({
               type: "POST",
               url: url,
               data: $("#add-variable-form").serialize(), // serializes the form's elements.
               success: function(data)
               {
            	   data = jQuery.parseJSON(data);
            	   $('#formModal').modal('hide');
            	   if (data.status =='SUCCESS') {
   	        		 	showLoading(data.result);
	   	           }
	   	           else {
	   	        	    showDialogue(data.result, data.status, O_OK);
	   	           }
	               $('#variablesTable').dataTable().api().ajax.reload();
	               hideLoading();
               }
             });
        return false; // avoid to execute the actual submit of the form.
    });
    
});

function changeItemStatus(id, status)
{
	AppChangeItemStatus(id, status, '#variablesTable', 'changeVarStatus');
}

function changeBatchItemStatus (action) {
	AppChangeBatchItemStatus (action, '#variablesTable');
}

function removeItems () {
	AppRemoveItems ('deletevariable');
}

function removeAllItems () {
	AppRemoveAllItems ('deleteAllVariables', '#variablesTable');
}

function loadData (action) {
	AppRunAction (action, '#variablesTable');	
}