var controller = "backup";
var option = "com_ose_firewall";
jQuery(document).ready(function($) {
	$('#backupTable').dataTable({
		processing : true,
		serverSide : true,
		ajax : {
			url : url,
			type : "POST",
			data : function(d) {
				d.option = option;
				d.controller = controller;
				d.action = 'getBackupList';
				d.task = 'getBackupList';
				d.centnounce = $('#centnounce').val();
			}
		},
		columns : [
            { "data" : "ID" },
            { "data" : "time" },
            { "data" : "fileName" },
            { "data" : "fileType" } ,
            { "data" : "downloadLink" },
            { "data" : null, "defaultContent" : " ", "orderable" : false, "searchable" : false	}
        ],
        order: [0, 'desc']
	});
	$('#checkbox').prop('checked', false);
	$('#backupTable tbody').on('click', 'tr', function() {
		$(this).toggleClass('selected');
	});
	$('#checkbox').click(function() {
		if ($('#checkbox').is(':checked')) {
			$('#backupTable tr').addClass('selected');
		} else {
			$('#backupTable tr').removeClass('selected');
		}
	});

});

function ajaxdeletebackup() {
	jQuery(document).ready(function($) {
        ids = $('#backupTable').dataTable().api().rows('.selected').data();
        multiids = [];
        index = 0;
        for (index = 0; index < ids.length; ++index) {
            multiids[index] = (ids[index]['ID']);
        }
        $.ajax({
            type : "POST",
            url : url,
            dataType : "json",
            data : {
                option : option,
                controller : controller,
                action : 'deleteBackup',
                task : 'deleteBackup',
                id : multiids,
                centnounce : $('#centnounce').val()
            },
            success : function(data) {
                if (data == true ) {
                    showDialogue(O_BACKUP_DELE_DESC, O_SUCCESS, O_OK);
                } else {
                    showDialogue(O_DELE_FAIL_DESC, O_FAIL, O_OK);
                }
                $('#backupTable').dataTable().api().ajax.reload();
            }
        });
    })
}
function deletebackup() {
	jQuery(document).ready(function($) {
        ids = $('#backupTable').dataTable().api().rows('.selected').data();
        if (ids.length > 0) {
            bootbox.dialog({
                message: O_DELETE_CONFIRM_DESC,
                title: O_CONFIRM,
                buttons : {
                    success : {
                        label: O_YES,
                        callback : function() {
                            ajaxdeletebackup();
                        }
                    },
                    main : {
                        label: O_NO,
                        callback : function() {
                            this.close();
                        }
                    }
                }
            });
        } else {
            showDialogue(O_SELECT_FIRST, O_NOTICE, O_NO);
        }
    })
}
function backup(backup_type, backup_to) {
	showLoading('Please wait...');
	jQuery(document).ready(function($) {
        $.ajax({
            type : "POST",
            url : url,
            dataType : 'json',
            data : {
                option : option,
                controller : controller,
                action : 'backup',
                task : 'backup',
                backup_type : backup_type,
                backup_to : backup_to,
                centnounce : $('#centnounce').val()
            },
            success : function(data) {
                if (data.data == false) {
                    hideLoading();
                    showDialogue(O_BACKUP_FAIL, O_FAIL, O_OK);
                }else if (data.conti == 1) {

                    contbackup(data.sourcePath, data.outZipPath, data.serializefile);

                }else /*if (typeof data.data == "number" && data.conti == 0 )*/ {
                    hideLoading();
                    showDialogue(O_BACKUP_SUCCESS, O_SUCCESS, O_OK);
                    $('#backupTable').dataTable().api().ajax.reload();
                }
            },
            error : function(request, textStatus, thrownError){
                hideLoading();
                showDialogue(O_BACKUP_ERROR + thrownError + "<br /><pre>" + request.responseText + "</pre>",
                    O_ERROR, O_OK);
            }
        })
    })
}

function contbackup(sourcePath, outZipPath, serializefile){
    showLoading('Archiving files, Please wait...');
    jQuery(document).ready(function($) {
        $.ajax({
            type : "POST",
            url : url,
            dataType : 'json',
            data : {
                option : option,
                controller : controller,
                action : 'contBackup',
                task : 'contBackup',
                sourcePath : sourcePath,
                outZipPath : outZipPath,
                serializefile : serializefile,
                centnounce : $('#centnounce').val()
            },
            success : function(data) {
                if (data.data == false) {
                    hideLoading();
                    showDialogue(O_BACKUP_FAIL, O_FAIL, O_OK);
                }else if (data.conti == 1) {
                    contbackup(data.sourcePath, data.outZipPath, data.serializefile);

                }else if (data.conti == 0 ) {
                    hideLoading();
                    showDialogue(O_BACKUP_SUCCESS, O_SUCCESS, O_OK);
                    $('#backupTable').dataTable().api().ajax.reload();
                }
            },
            error : function(request, textStatus, thrownError){
                hideLoading();
                showDialogue(O_BACKUP_ERROR + thrownError + "<br /><pre>" + request.responseText + "</pre>",
                    O_ERROR, O_OK);
            }
        })
    })
}
