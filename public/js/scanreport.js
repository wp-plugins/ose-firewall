var controller = "scanreport";
var option = "com_ose_firewall";

jQuery(document).ready(function ($) {
    var scanreportDataTable = $('#scanreportTable').dataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function (d) {
                d.option = option;
                d.controller = controller;
                d.action = 'getMalwareMap';
                d.task = 'getMalwareMap';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [
            {"data": "file_id"},
            {"data": "filename"},
            {"data": "patterns"},
            {"data": "checked"},
            {"data": "pattern_id"},
            {"data": "confidence"},
            {"data": "view"},
            {
                "data": null,
                "defaultContent": " ",
                "orderable": false,
                "searchable": false
            }
        ]
    });
    $('#checkbox').prop('checked', false);
    $('#scanreportTable tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
    });
    $('#checkbox').click(function () {
        if ($('#checkbox').is(':checked')) {
            $('#scanreportTable tr').addClass('selected');
        } else {
            $('#scanreportTable tr').removeClass('selected');
        }
    });
    var statusFilter = $('<label>Status: <select name="statusFilter" id="statusFilter"><option value=""></option><option value="0 ">No action</option><option value="1">Cleaned</option><option value="2">Quarantined</option></select></label>');
    statusFilter.appendTo($("#scanreportTable_filter")).on('change', function () {
        var val = $('#statusFilter');
        scanreportDataTable.api().column(3)
            .search(val.val(), false, false)
            .draw();
        if (val.val() == "2") {
            document.getElementById("delete-button").style.display = 'inline';
        }
        else {
            document.getElementById("delete-button").style.display = 'none';
        }
    });
    $('#filecontentModal').on('hidden.bs.modal', function () {
        $('#scanreportTable').dataTable().api().ajax.reload();
    });
});

function batchquarantine() {
    showLoading();
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;
        if (ids.length > 0) {
            for (index = 0; index < ids.length; ++index) {
                multiids[index] = (ids[index]['file_id']);
            }
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {
                    option: option,
                    controller: controller,
                    action: 'batchqt',
                    task: 'batchqt',
                    id: multiids,
                    centnounce: $('#centnounce').val()
                },
                success: function (data) {
                    hideLoading();
                    if (data.data == 1) {
                        showLoading(O_QUARANTINE_SUCCESS_DESC);
                        $('#scanreportTable').dataTable().api().ajax.reload();
                    } else {
                        showLoading(O_QUARANTINE_FAIL_DESC);
                    }
                    hideLoading();
                    $('#checkbox').prop('checked', false);
                }
            });
        } else {
            hideLoading();
            showDialogue(O_SELECT_FIRST, O_NOTICE, O_OK);
        }
    })
}
function batchbkcl() {
    showLoading();
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;
        if (ids.length > 0) {
            for (index = 0; index < ids.length; ++index) {
                multiids[index] = (ids[index]['file_id']);
            }
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {
                    option: option,
                    controller: controller,
                    action: 'batchbkcl',
                    task: 'batchbkcl',
                    id: multiids,
                    centnounce: $('#centnounce').val()
                },
                success: function (data) {
                    hideLoading();
                    if (data.data == 'success') {
                        showLoading(O_CLEAN_SUCCESS);
                        $('#scanreportTable').dataTable().api().ajax.reload();
                    } else {
                        showLoading(O_CLEAN_FAIL);
                    }
                    hideLoading();
                    $('#checkbox').prop('checked', false);
                }
            });
        } else {
            hideLoading();
            showDialogue(O_SELECT_FIRST, O_NOTICE, O_OK);
        }
    })
}
function batchrs() {
    showLoading();
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;
        if (ids.length > 0) {
            for (index = 0; index < ids.length; ++index) {
                multiids[index] = (ids[index]['file_id']);
            }
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {
                    option: option,
                    controller: controller,
                    action: 'batchrs',
                    task: 'batchrs',
                    id: multiids,
                    centnounce: $('#centnounce').val()
                },
                success: function (data) {
                    hideLoading();
                    if (data.data == 'success') {
                        showLoading(O_RESTORE_SUCCESS);
                        $('#scanreportTable').dataTable().api().ajax.reload();
                    } else {
                        showLoading(O_RESTORE_FAIL);
                    }
                    hideLoading();
                    $('#checkbox').prop('checked', false);
                }
            });
        } else {
            hideLoading();
            showDialogue(O_SELECT_FIRST, O_NOTICE, O_OK);
        }
    })
}
function confirmbatchdl() {
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        if (ids.length > 0) {
            bootbox
                .dialog({
                    message: O_DELETE_CONFIRM_DESC,
                    title: O_CONFIRM,
                    buttons: {
                        success: {
                            label: O_YES,
                            callback: function () {
                                batchdl();
                            }
                        },
                        main: {
                            label: O_NO,
                            callback: function () {
                                this.close();
                            }
                        }
                    }
                });
        } else {
            showDialogue(O_SELECT_FIRST, O_NOTICE, O_OK);
        }
    })
}
function batchdl() {
    showLoading();
    jQuery(document).ready(function ($) {
        ids = $('#scanreportTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;
        for (index = 0; index < ids.length; ++index) {
            multiids[index] = (ids[index]['file_id']);
        }
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'batchdl',
                task: 'batchdl',
                id: multiids,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == 1) {
                    showLoading(O_DELE_SUCCESS_DESC);
                } else {
                    showLoading(O_DELE_FAIL_DESC);
                }
                hideLoading();
                $('#checkbox').prop('checked', false);
                $('#scanreportTable').dataTable().api().ajax.reload();
            }
        });
    })
}
function viewFiledetail(id, status) {
    showLoading();
    jQuery(document).ready(function ($) {
        $('#filecontentModal').modal();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'viewfile',
                task: 'viewfile',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                var newtext = data.data;
                var newtext = "<pre>" + newtext + "</pre>";
                var re = /&lt;span class=&#039;bg-warning&#039;&gt;/img;
                var subst = '<span class=\'bg-warning\'>';
                var result = newtext.replace(re, subst);

                var re1 = /&lt;\/span&gt;/img;
                var subst1 = '</span>';
                var result1 = result.replace(re1, subst1);
                if (status == 0) {
                    var buttons =
                        "<button type='button' class='btn btn-primary' onclick='bkcleanvs(" + id + ", 1" + ")'>" + O_CLEAN + "</button>" +
                        "<button type='button' class='btn btn-primary' onclick='quarantinevs(" + id + ", 2" + ")'>" + O_QUARANTINE + "</button>" +
                        "<button type='button' class='btn btn-default' data-dismiss='modal'>" + O_CLOSE + "</button>";
                } else if (status == 1) {
                    var buttons =
                        "<button type='button' class='btn btn-primary' onclick='restorevs(" + id + ", 0" + ")'>" + O_RESTORE + "</button>" +
                        "<button type='button' class='btn btn-primary' onclick='quarantinevs(" + id + ", 2" + ")'>" + O_QUARANTINE + "</button>" +
                        "<button type='button' class='btn btn-default' data-dismiss='modal'>" + O_CLOSE + "</button>";
                }
                else {
                    var buttons =
                        "<button type='button' class='btn btn-primary' onclick='restorevs(" + id + ", 0" + ")'>" + O_RESTORE + "</button>" +
                        "<button type='button' class='btn btn-primary' onclick='confirmdeletevs(" + id + ")'>" + O_DELETE + "</button>" +
                        "<button type='button' class='btn btn-default' data-dismiss='modal'>" + O_CLOSE + "</button>";
                }
                $('#codeareaDiv').html(result1);
                $('#buttonDiv').html(buttons);
            }
        });

    });
}
function restorevs(id, status) {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'restorevs',
                task: 'restorevs',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == 'success') {
                    showLoading(O_RESTORE_SUCCESS);
                    viewFiledetail(id, status);
                }
                else {
                    showLoading(O_RESTORE_FAIL);
                }
                hideLoading();
            }
        })
    })
}
function deletevs(id) {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'deletevs',
                task: 'deletevs',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == 1) {
                    showLoading(O_DELE_SUCCESS_DESC);
                    $('#filecontentModal').modal('hide');
                }
                else {
                    showLoading(O_DELE_FAIL_DESC);
                }
                hideLoading();
            }
        })
    })
}
function quarantinevs(id, status) {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'quarantinevs',
                task: 'quarantinevs',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == 1) {
                    showLoading(O_QUARANTINE_SUCCESS_DESC);
                    viewFiledetail(id, status);
                } else {
                    showLoading(O_QUARANTINE_FAIL_DESC);
                }
                hideLoading();
            }
        })
    })
}
function bkcleanvs(id, status) {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'bkcleanvs',
                task: 'bkcleanvs',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == 'success') {
                    showLoading(O_CLEAN_SUCCESS);
                    viewFiledetail(id, status);
                } else {
                    showLoading(O_CLEAN_FAIL);
                }
                hideLoading();
            }
        })
    })
}
function confirmdeletevs(id, status) {
    bootbox
        .dialog({
            message: O_DELETE_CONFIRM_DESC,
            title: O_NOTICE,
            buttons: {
                success: {
                    label: O_YES,
                    callback: function () {
                        deletevs(id, status);
                    }
                },
                main: {
                    label: O_NO,
                    callback: function () {
                        this.close();
                    }
                }
            }
        });
}
