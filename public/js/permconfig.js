//var url = ajaxurl;
var controller = "permconfig";
var option = "com_ose_firewall";
var view = "permconfig";
var permconfigDataTable;
var selectedids =  [];

jQuery(document).ready(function($){
    getcurrentdirectory('/');
	function getcurrentdirectory (dir) {
        permconfigDataTable = $('#permconfigTable').dataTable({
            processing: true,
            serverSide: false,
            destroy: true,
            ajax: {
                url: url,
                type: "POST",
                data: function (d) {
                    d.option = option;
                    d.controller = controller;
                    d.action = 'getDirFileList';
                    d.task = 'getDirFileList';
                    d.dir = dir;
                    d.centnounce = $('#centnounce').val();
                }
            },
            columns: [
                {"data": "dirsort", width: '5%', visible: false},
                {"data": "icon", width: '1%', sortable: false},
                {"data": "name", width: '15%'},
                {"data": "type", width: '5%'},
                {"data": "groupowner", width: '5%'},
                {"data": "perm", width: '5%'},
                {"data": "path", width: '5%', visible: false}
            ],
            lengthMenu: [[-1, 10, 25, 50], ["All", 10, 25, 50]],
            order: [[0, 'asc'], [2, 'asc']],
            "createdRow": function ( row, data, index ) {
                selectedids.forEach(function(entry) {
                    if (data['path'] === entry.replace('dir:', '')) {
                        $(row).toggleClass('selected');
                    }
                })
            }
        });
    }
    $('#permconfigTable tbody').on( 'click', 'tr', function () {
        if (permconfigDataTable.fnGetData( this ) !==null) {
            $(this).toggleClass('selected');
            var path = permconfigDataTable.fnGetData(this)['path'];
            var type = permconfigDataTable.fnGetData(this)['type'];
            if (type === 'dir') {
                var index = $.inArray('dir:' + path, selectedids);
            } else {
                var index = $.inArray(path, selectedids);
            }
            /* Update the data array and return the value */
            if (index === -1) {
                if (type === 'dir') {
                    selectedids.push('dir:' + path);
                } else {
                    selectedids.push(path);
                }
            } else {
                selectedids.splice(index, 1);
            }
            console.log(selectedids);
        }
    });
    $( '#FileTreeDisplay' ).html( '<ul class="filetree start"><li class="wait">' + 'Generating Tree...' + '<li></ul>' );
    getfilelist( $('#FileTreeDisplay') , '' );
    function clickfiletreedisplay (entry, current, rel_id, refreshtable){
        var currentfolder;
        currentfolder = getfiletreedisplay (entry, current, rel_id);
        if(typeof currentfolder == 'undefined'){
            currentfolder = '';
        }
        if (refreshtable){
            $( '#selected_file' ).text( "Current Folder: ROOT" + currentfolder);
            getcurrentdirectory(escape( current.attr(rel_id) ));
        }
    }
    $( '#FileTreeDisplay' ).on('click', 'LI', function() { /* monitor the click event on foldericon */
        var entry = $(this);
        var current = $(this);
        var id = 'id';
        clickfiletreedisplay(entry, current, id, false);
        return false;
    });
    $( '#FileTreeDisplay' ).on('click', 'LI A', function() { /* monitor the click event on links */
        var entry = $(this).parent();
        var current = $(this);
        var rel = 'rel';
        clickfiletreedisplay(entry,current,rel, true);
        return false;
    });
    $("#edit-perm-form").submit(function() {
        var binary = 0+document.editpermform.u.value + document.editpermform.g.value + document.editpermform.w.value;
        document.editpermform.chmodbinary.value = binary;
        if (selectedids.length < 1){
            $('#editpermModal').modal('hide');
            showDialogue ('Please select some Files or Folders', 'ERROR', 'OK');
        }else if(binary === '0000') {
            $('#editpermModal').modal('hide');
            showDialogue ('Make sure to set appropriate file permissions 0000 would render your selected Files/Folders inaccessible', 'ERROR', 'OK');
        }else{
            showLoading ();
            $.ajax({
                url: url,
                type: "POST",
                data: $("#edit-perm-form").serialize(), // serializes the form's elements.
                success: function(data) {
                    data = jQuery.parseJSON(data);
                    $('#editpermModal').modal('hide');
                    hideLoading ();
                    showDialogue (data.result, data.status, 'OK');
                    $('#permconfigTable').dataTable().api().ajax.reload();
                }
            });
        }
        return false; // avoid to execute the actual submit of the form.
    });
});

function getselecteditemslist (){
    var files = '';
    var folders = '';
    var selectedvalues = [];
    calcperm();
    disableradios();
    selectedids.forEach(function(entry) {
        if (entry.indexOf('dir:') === -1) {
            files += entry + ',  ';
            selectedvalues.push(entry);
        } else {
            folders += entry.replace('dir:', '') + ',  ';
            selectedvalues.push(entry.replace('dir:', ''));
        }
    });
    document.editpermform.chmodpaths.value = selectedids.join([separator = '{/@^}']); /*set dir value for ajax chmod call*/
    jQuery( '#SelectedItemsList').html( "<h5>Folder(s): <small>"+ folders +"</small></h5> <h5>File(s): <small>"+ files +"</small></h5>");
}

function calcperm() {
    document.editpermform.u.value = 0;
    if (document.editpermform.ur.checked) {
            document.editpermform.u.value = document.editpermform.u.value * 1 + document.editpermform.ur.value * 1;
    }
    if (document.editpermform.uw.checked) {
            document.editpermform.u.value = document.editpermform.u.value * 1 + document.editpermform.uw.value * 1;
    }
    if (document.editpermform.ux.checked) {
            document.editpermform.u.value = document.editpermform.u.value * 1 + document.editpermform.ux.value * 1;
    }
    document.editpermform.g.value = 0;
    if (document.editpermform.gr.checked) {
            document.editpermform.g.value = document.editpermform.g.value * 1 + document.editpermform.gr.value * 1;
    }
    if (document.editpermform.gw.checked) {
            document.editpermform.g.value = document.editpermform.g.value * 1 + document.editpermform.gw.value * 1;
    }
    if (document.editpermform.gx.checked) {
            document.editpermform.g.value = document.editpermform.g.value * 1 + document.editpermform.gx.value * 1;
    }
    document.editpermform.w.value = 0;
    if (document.editpermform.wr.checked) {
            document.editpermform.w.value = document.editpermform.w.value * 1 + document.editpermform.wr.value * 1;
    }
    if (document.editpermform.ww.checked) {
            document.editpermform.w.value = document.editpermform.w.value * 1 + document.editpermform.ww.value * 1;
    }
    if (document.editpermform.wx.checked) {
            document.editpermform.w.value = document.editpermform.w.value * 1 + document.editpermform.wx.value * 1;
    }
}

function disableradios() {
    if ( !!document.getElementById("recur")) {
        jQuery('input:radio').attr('disabled', !recur.checked);
        jQuery('input:radio').attr('checked', recur.checked);
        if (!recur.checked) {
            jQuery('label[for=recurall]').css({color: '#A4A4A4'});
            jQuery('label[for=recurfiles]').css({color: '#A4A4A4'});
            jQuery('label[for=recurfolders]').css({color: '#A4A4A4'});
        }
        else {
            jQuery('label[for=recurall]').css({color: '#000000'});
            jQuery('label[for=recurfiles]').css({color: '#000000'});
            jQuery('label[for=recurfolders]').css({color: '#000000'});
            document.getElementById("recurall").checked = true;
        }
    }
}

function oneClickPermFix (){
    bootbox.dialog({
            title: O_FIXPERMISSIONS_LONG,
            message: O_FIXPERMISSIONS_DESC,
            buttons: {
                success: {
                    label: O_FIXPERMISSIONS,
                    className: "btn-success",
                    callback: function () {
                        showLoading('Fixing...');
                        AppRunAction('oneClickFixPerm', '#permconfigTable');
                        hideLoading();
                    }
                },
                main: {
                    label: O_CANCEL,
                    className: "btn-alt",
                    callback: function(result) {
                        this.close();
                    }
                }
            }
        }
    );

}

function callToSubscribe(loginurl){
    bootbox.dialog({
        message: O_CALLTOSUBSCRIBE_DESC,
        title: O_CALLTOSUBSCRIBE,
        buttons: {
            success: {
                label: O_SUBSCRIBE,
                className: "btn-success",
                callback: function(result) {
                    redirectTut(loginurl);
                }
            },
            main: {
                label: O_CANCEL,
                className: "btn-alt",
                callback: function(result) {
                    this.close();
                }
            }
        }
    });
}
