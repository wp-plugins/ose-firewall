var url = ajaxurl; 
var controller = "countryblock";
var option = "com_ose_firewall";

jQuery(document).ready(function($){
    var rulesetsDataTable = $('#countryTable').dataTable( {
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function ( d ) {
                d.option = option;
                d.controller = controller;
                d.action = 'getCountryList';
                d.task = 'getCountryList';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [
                { "data": "id", width: '5%'},
                { "data": "country_code", width: '5%'},
                { "data": "name"},
                { "data": "status", width: '5%'},
                { "data": "checkbox", sortable: false , width: '5%'}
        ]
    });
    $('#countryTable tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    });
    $('#checkedAll').on('click', function() {
    	$('#countryTable').dataTable().api().rows()
        .nodes()
        .to$()
        .toggleClass('selected');
    })
    var statusFilter = $('<label>Status: <select name="statusFilter" id="statusFilter"><option value="-1"></option><option value="1">Blacklisted</option><option value="2">Monitored</option><option value="3">Whitelisted</option></select></label>');
    statusFilter.appendTo($("#countryTable_filter")).on( 'change', function () {
        var val = $('#statusFilter');
         rulesetsDataTable.api().column(3)
            .search( val.val(), false, false )
            .draw();
    });
    $("#download-geoip-form").submit(function() {
    	downLoadFile($, 8)
        return false; // avoid to execute the actual submit of the form.
    });
    $('.progress-circular-blue').circliful({backgroundColor: '#ECF0F1',  foregroundColor: '#1E8BC3'});    
});


function downLoadFile($, step) {
	$('#message-box').waitMe({
	        effect : 'facebook',
	        text : 'Please wait...',
	        bg : 'rgba(255,255,255,0.7)',
	        color : '#1BBC9B'
	});
	$.ajax({
        type: "POST",
        url: url,
        data: {
        	option:option,
            controller:controller,
            action:'downLoadTables',
            task:'downLoadTables',
            step:step,
            centnounce:$('#centnounce').val()
        }, 
        success: function(data)
        {
           data = jQuery.parseJSON(data);
           $('#message-box').waitMe("hide");
           $('#message-box').html(data.result);
     	   if (data.status == 'unfinish')
     	   {
     		  var pct = Math.round((1-(step-1)/8)*100);
     		  $('.progress-circular-blue').empty().removeData().attr('data-text', pct+'%');
              $('.progress-circular-blue').empty().removeData().attr('data-percent', pct).circliful({backgroundColor: '#ECF0F1',  foregroundColor: '#1E8BC3'});
              downLoadFile($, step-1);
     	   }
     	   else
     	   {
     		  var pct = 100;
     		  $('.progress-circular-blue').empty().removeData().attr('data-text', pct+'%');
              $('.progress-circular-blue').empty().removeData().attr('data-percent', pct).circliful({backgroundColor: '#ECF0F1',  foregroundColor: '#1E8BC3'});
     		  createTables ($, 0);
     	   }
        }
      });
}

function createTables($, step) {
	$('#message-box').waitMe({
	        effect : 'facebook',
	        text : 'Please wait...',
	        bg : 'rgba(255,255,255,0.7)',
	        color : '#1BBC9B'
	});
	$.ajax({
        type: "POST",
        url: url,
        data: {
        	option:option,
            controller:controller,
            action:'createTables',
            task:'createTables',
            step:step,
            centnounce:$('#centnounce').val()
        }, 
        success: function(data)
        {
           data = jQuery.parseJSON(data);
           $('#message-box').waitMe("hide");
           $('#message-box').html(data.result);
     	   if (data.cont == 1)
     	   {
     		  var pct = Math.round((step/10)*100);
              $('.progress-circular-blue').empty().removeData().attr('data-text', pct+'%');
              $('.progress-circular-blue').empty().removeData().attr('data-percent', pct).circliful({backgroundColor: '#ECF0F1',  foregroundColor: '#1E8BC3'});
     		  createTables($, step+1);
     	   }
     	   else
     	   {
     		  $('.progress-circular-blue').empty().removeData().attr('data-text', '100%');
              $('.progress-circular-blue').empty().removeData().attr('data-percent', 100).circliful({backgroundColor: '#ECF0F1',  foregroundColor: '#1E8BC3'});
              $('#message-box').html('Completed');
              $('#countryTable').dataTable().api().ajax.reload();
              $('#formModal').modal('hide');
              showDialogue ('CountryBlock Database Completed', 'Completed', 'OK');
     	   }
        }
      });
}

function changeItemStatus(id, status)
{
	AppChangeItemStatus(id, status, '#countryTable', 'changeCountryStatus');
}

function changeBatchItemStatus (action) {
	AppChangeBatchItemStatus (action, '#countryTable');
}

function removeItems () {
	AppRemoveItems ('deleteCountry');
}

function removeAllItems () {
	AppRemoveAllItems ('deleteAllCountry', '#countryTable');
}

function loadData (action) {
	AppRunAction (action, '#countryTable');	
}