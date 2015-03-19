var controller = "scanreport";
var option = "com_ose_firewall";

jQuery(document).ready(function ($) {
    $('#scanreportTable').dataTable({
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
            {"data": "pattern_id"},
            {"data": "confidence"},
            {"data": "view"},
            {"data": "checkbox"}
        ]
    });
});

function viewFiledetail(id) {
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
                var newtext = "<pre>" + newtext + "</pre>"
                var re = /&lt;span class=&#039;bg-warning&#039;&gt;/img;
                var subst = '<span class=\'bg-warning\'>';
                var result = newtext.replace(re, subst);

                var re1 = /&lt;\/span&gt;/img;
                var subst1 = '</span>';
                var result1 = result.replace(re1, subst1);

                var buttons =
                    "<button type='button' class='btn btn-primary' onclick='virusbackup(" + id + ")'>Back Up</button>" +
                    "<button type='button' class='btn btn-primary' onclick='bkcleanvs(" + id + ")'>BackUp and Clean</button>" +
                    "<button type='button' class='btn btn-primary' onclick='deletevs(" + id + ")'>Delete</button>" +
                    "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>";

                $('#codeareaDiv').html(result1);
                $('#buttonDiv').html(buttons);
//	           var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('codearea'), {
//	        	   lineNumbers: true,
//	               matchBrackets: true,
//	               mode: "application/x-httpd-php",
//	               indentUnit: 4,
//	               indentWithTabs: true
//	           });
            }
        });

    });
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
                    alert("virus file has been deleted!");
                    $('#filecontentModal').modal('hide');
                    $('#scanreportTable').dataTable().api().ajax.reload();
                }
                else {
                    alert("delete failed!");
                }
            }
        })
    })
}
function bkcleanvs(id) {
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
                if (data.data == "back up fail!") {
                    alert(data.data);
                } else {
                    var content = data.data;
                    $('#codeareaDiv').html(content);
                }
            }
        })
    })
}

function virusbackup(id) {
    showLoading();
    jQuery(document).ready(function ($) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: controller,
                action: 'backupvs',
                task: 'backupvs',
                id: id,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                if (data.data == "success") {
                    alert(data.data);
                } else {
                    alert(data.data);
                }
            }
        })
    })
}
