var url = ajaxurl; 
var controller = "rulesets";
var option = "com_ose_firewall";

jQuery(document).ready(function($){
    var rulesetsDataTable = $('#rulesetsTable').dataTable( {
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
                { "data": "rule" },
                { "data": "attacktype" },
                { "data": "action" },
                { "data": "checkbox", sortable: false }
        ]
    });
    $('#rulesetsTable tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    });
    $('#checkedAll').on('click', function() {
    	$('#rulesetsTable').dataTable().api().rows()
        .nodes()
        .to$()
        .toggleClass('selected');
    })
    var statusFilter = $('<label>Status: <select name="statusFilter" id="statusFilter"><option value="-1"></option><option value="1">Active</option><option value="0">InActive</option></select></label>');
    statusFilter.appendTo($("#rulesetsTable_filter")).on( 'change', function () {
        var val = $('#statusFilter');
         rulesetsDataTable.api().column(3)
            .search( val.val(), false, false )
            .draw();
    });
});

function changeItemStatus(id, status)
{
	AppChangeItemStatus(id, status, '#rulesetsTable', 'changeRuleStatus');
}

tinymce.init({
    selector: "textarea.tinymce",
    menubar : false,
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code ",
        "insertdatetime table contextmenu paste"
    ],
    height: 200,
    toolbar: "bold italic strikethrough bullist numlist blockquote hr alignleft aligncenter alignright alignjustify link unlink code image media | fullscreen"
});