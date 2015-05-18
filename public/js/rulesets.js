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
    //var myElem = document.getElementById('HideQR');
    //if (myElem == null) {
    //
    //} else {
    //    if (document.getElementById("HideQR").checked == false) {
    //        document.getElementById("hidden-QRcode").style.display = "block";
    //    } else {
    //        document.getElementById("hidden-QRcode").style.display = "none";
    //    }
    //}
});
function changeItemStatus(id, status)
{
	AppChangeItemStatus(id, status, '#rulesetsTable', 'changeRuleStatus');
}
function showSecret() {
    //if (document.getElementById("HideQR").checked == false) {
    //    document.getElementById("hidden-QRcode").style.display = "block";
    //    //  showGoogleSecret();
    //} else {
    //    document.getElementById("hidden-QRcode").style.display = "none";
    //}
}
//function showGoogleSecret() {
//    jQuery(document).ready(function ($) {
//        $.ajax({
//            type: "POST",
//            url: url,
//            dataType: 'json',
//            data: {
//                option: option,
//                controller: 'scanconfig',
//                action: 'showGoogleSecret',
//                task: 'showGoogleSecret',
//                centnounce: $('#centnounce').val()
//            },
//            success: function (data) {
//                document.getElementById('shhsecret').innerHTML = data.secret;
//                document.getElementById('shhqrcode').innerHTML = data.QRcode;
//            }
//        });
//    });
//}
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