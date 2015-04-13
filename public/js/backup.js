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
		columns : [ {
			"data" : "ID"
		}, {
			"data" : "time"
		},{
			"data" : "fileName"
		},  {
			"data" : "fileType"
		} ,{
			"data" : "downloadLink"
		}, {
			"data" : null,
			"defaultContent" : " ",
			"orderable" : false,
			"searchable" : false
		} ]
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
	jQuery(document).ready(
					function($) {
						ids = $('#backupTable').dataTable().api().rows('.selected').data();
						multiids = [];
						index = 0;
						for (index = 0; index < ids.length; ++index) {
							multiids[index] = (ids[index]['ID']);
						}
						$.ajax({
									type : "POST",
									url : url,
                                    dataType:"json",
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
											showDialogue(
													"The backup file has been deleted successfully",
													"Great!", 'OK');
										} else {
											showDialogue(
													"The delete operation failed! Please try again",
													"FAIL!", 'OK');
										}
										$('#backupTable').dataTable().api().ajax
												.reload();
									}
								});
					})
}
function deletebackup() {
	jQuery(document).ready(
					function($) {
						ids = $('#backupTable').dataTable().api().rows('.selected').data();
						if (ids.length > 0) {
							bootbox.dialog({
										message : "Are you sure you want to delete the selected file(s), press yes to continue",
										title : "Confirm",
										buttons : {
											success : {
												label : "Yes",
												callback : function() {
													ajaxdeletebackup();
												}
											},
											main : {
												label : "No",
												callback : function() {
													this.close();
												}
											}
										}
									});
						} else {
							showDialogue(
									"Please select backup zip or sql files first!",
									"Notice!", 'OK');
						}
					})
}
function backup(backup_type, backup_to) {
	showLoading('Please wait...');
	jQuery(document).ready(
			function($) {
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
						hideLoading();
						if (data.data == true) {
							showDialogue("Backup successful", "Success", "OK");
							$('#backupTable').dataTable().api().ajax.reload();
						} else {
							showDialogue("Backup failed, please try again",
									"Notice", "OK");
						}
					}
				})
			})
}
function showDialogue(message, title, buttonLabel) {
	bootbox.dialog({
		message : message,
		title : title,
		buttons : {
			success : {
				label : buttonLabel
			}
		}
	});
}