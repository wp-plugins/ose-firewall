var url = ajaxurl; 
var controller = "advancerulesets";
var option = "com_ose_firewall";

jQuery(document).ready(function($){
    var rulesetsDataTable = $('#AdvrulesetsTable').dataTable( {
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function ( d ) {
                d.option = option;
                d.controller = controller;
                d.action = 'getRulesets';
                d.task = 'getRulesets';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [
                { "data": "id" },
                { "data": "description" },
                { "data": "attacktype" },
                { "data": "impact" },
                { "data": "action" },
                { "data": "checkbox", sortable: false }
        ]
    });
    $('#AdvrulesetsTable tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    });
    $('#checkedAll').on('click', function() {
    	$('#AdvrulesetsTable').dataTable().api().rows()
        .nodes()
        .to$()
        .toggleClass('selected');
    })
    var statusFilter = $('<label>Status: <select name="statusFilter" id="statusFilter"><option value="-1"></option><option value="1">Active</option><option value="0">InActive</option></select></label>');
    statusFilter.appendTo($("#AdvrulesetsTable_filter")).on( 'change', function () {
        var val = $('#statusFilter');
         rulesetsDataTable.api().column(4)
            .search( val.val(), false, false )
            .draw();
    });
});

function changeItemStatus(id, status)
{
	AppChangeItemStatus(id, status, '#AdvrulesetsTable', 'changeRuleStatus');
}

function downloadRequest(type) {
    jQuery(document).ready(function ($) {
        showLoading();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'downloadRequest',
                task: 'downloadRequest',
                type: type,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                downloadSQL(type, data.downloadKey);
            }
        });
    });
}
function downloadSQL(type, downloadKey) {
    jQuery(document).ready(function ($) {
        showLoading('Signature is being updated, please wait...');
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'downloadSQL',
                task: 'downloadSQL',
                type: type,
                downloadKey: downloadKey,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                showLoading(data.result);
            	hideLoading();
                $('#AdvrulesetsTable').dataTable().api().ajax.reload();
            }
        });
    });
}
