var url = ajaxurl;
var controller = "upload";
var option = "com_ose_firewall";

jQuery(document).ready(function ($) {
    var fileextDataTable = $('#extensionListTable').dataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function (d) {
                d.option = option;
                d.controller = controller;
                d.action = 'getExtLists';
                d.task = 'getExtLists';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [
            {"data": "ext_id"},
            {"data": "ext_name"},
            {"data": "ext_type"},
            {"data": "ext_status"}
        ]
    });

    $('#extensionListTable tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
    });
    var statusFilter = $('<label>Status: <select name="statusFilter" id="statusFilter"><option value="0"></option><option value="1">Allowed</option><option value="2">Forbidden</option></select></label>');
    statusFilter.appendTo($("#extensionListTable_filter")).on('change', function () {
        var val = $('#statusFilter');
        fileextDataTable.api().column(3)
            .search(val.val(), false, false)
            .draw();
    });
    var typeFilter = $('<label>Type: <select name="typeFilter" id="typeFilter"><option value="0"></option><option value="Text Files">Text Files</option><option value="Data Files">Data Files</option><option value="Audio Files">Audio Files</option><option value="Video Files">Video Files</option>' +
    '<option value="3D Image Files">3D Image Files</option><option value="Raster Image Files">Raster Image Files</option><option value="Vector Image Files">Vector Image Files</option><option value="Page Layout Files">Page Layout Files</option><option value="Spreadsheet Files">Spreadsheet Files</option>' +
    '<option value="Database Files">Database Files</option><option value="Executable Files">Executable Files</option><option value="Game Files">Game Files</option><option value="CAD Files">CAD Files</option><option value="GIS Files">GIS Files</option><option value="Web Files">Web Files</option>' +
    '<option value="Plugin Files">Plugin Files</option><option value="Font Files">Font Files</option><option value="System Files">System Files</option><option value="Settings Files">Settings Files</option><option value="Encoded Files">Encoded Files</option><option value="Compressed Files">Compressed Files</option>' +
    '<option value="Disk Image Files">Disk Image Files</option><option value="Developer Files">Developer Files</option><option value="Backup Files">Backup Files</option><option value="Misc Files">Misc Files</option></select></label>');
    typeFilter.appendTo($("#extensionListTable_filter")).on('change', function () {
        var val = $('#typeFilter');
        fileextDataTable.api().column(2)
            .search(val.val(), false, false)
            .draw();
    });
});
function addExt() {
    jQuery(document).ready(function ($) {
        $('#addExtModal').modal();
    })
}
function changeStatus(status, id) {
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'changeStatus',
                task: 'changeStatus',
                status: status,
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {

                if (status == 0) {
                    document.getElementById(id).onclick = function () {
                        changeStatus(1, id);
                    };
                    document.getElementById(id).innerHTML = '<div class="fa fa-times color-red">';
                } else {
                    document.getElementById(id).onclick = function () {
                        changeStatus(0, id);
                    };
                    document.getElementById(id).innerHTML = '<div class="fa fa-check color-green">';
                }
            }
        })
    });
}

//******************** Upload log datatable **********************


jQuery(document).ready(function ($) {
    var extLogDataTable = $('#uploadLogTable').dataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function (d) {
                d.option = option;
                d.controller = controller;
                d.action = 'getLog';
                d.task = 'getLog';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [
            {"data": "id"},
            {"data": "ip_name"},
            {"data": "file_name"},
            {"data": "ext_name"},
            {"data": "validation_status"},
            //{"data": "vs_scan_status"},
            {"data": "datetime"},
        ]
    });
    $('#uploadLogTable tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
    });
    var adstatusFilter = $('<label>Status: <select name="adstatusFilter" id="adstatusFilter"><option value="-1"></option><option value="1">Active</option><option value="0">InActive</option></select></label>');
    adstatusFilter.appendTo($("#AdvrulesetsTable_filter")).on('change', function () {
        var val = $('#adstatusFilter');
        adrulesetsDataTable.api().column(4)
            .search(val.val(), false, false)
            .draw();
    });
});

