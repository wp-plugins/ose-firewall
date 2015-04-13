//var url = ajaxurl;
var controller = "permconfig";
var option = "com_ose_firewall";
var view = "permconfig";
var permconfigDataTable;
var selectedids =  [];

jQuery(document).ready(function($){
    getcurrentdirectory();
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
                {"data": "name", width: '15%'/*, order: 'asc'*/},
                {"data": "type", width: '5%'},
                {"data": "groupowner", width: '5%'},
                {"data": "perm", width: '5%'},
                {"data": "path", width: '5%', visible: false}
            ],
            lengthMenu: [[-1, 10, 25, 50], ["All", 10, 25, 50]],
           /* order: [[0, 'asc'], [2, 'asc']],*/
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
        if (permconfigDataTable.fnGetData( this )['path'] !=='') {
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

                //$(this).toggleClass('selected');
            } else {
                selectedids.splice(index, 1);
                //$(this).toggleClass('selected');
            }
            console.log(selectedids);
        }
    });

    $( '#FileTreeDisplay' ).html( '<ul class="filetree start"><li class="wait">' + 'Generating Tree...' + '<li></ul>' );

    getfilelist( $('#FileTreeDisplay') , '/' );

    function getfilelist( cont, root ) {

        $( cont ).addClass( 'wait' );

        $.ajax ({
            url: url,
            type: "POST",
            data: {
                option : option,
                controller:controller,
                action : 'getFileTree',
                task : 'getFileTree',
                centnounce : $('#centnounce').val(),
                dir: root
                //,view: view
            },
            success: function(data) {
                $( cont ).find( '.start' ).html( '' );
                $( cont ).removeClass( 'wait' ).append(data);
                if( '/' == root )
                    $( cont ).find('UL:hidden').show();
                else{
                    $( cont ).find('UL:hidden').slideDown({ duration: 500, easing: null });
                }
            }
        });
    }
    function clickfiletreedisplay (entry, current, rel_id, refreshtable){
        /*Don't expand Root*/
        if (escape( current.attr(rel_id) ) === '/'){
            entry.find('UL').slideUp({ duration: 1, easing: null }); /* collapse it */
            entry.removeClass('collapsed').addClass('expanded');
        }
        if( entry.hasClass('folder') ) { /* check if it has folder as class name */
            if( entry.hasClass('collapsed') ) { /* check if it is collapsed */

                entry.find('UL').remove(); /* if there is any UL remove it */
                getfilelist( entry, escape( current.attr(rel_id) )); /* initiate Ajax request */
                entry.removeClass('collapsed').addClass('expanded'); /* mark it as expanded */
            }
            else { /* if it is expanded already */
                entry.find('UL').slideUp({ duration: 500, easing: null }); /* collapse it */
                entry.removeClass('expanded').addClass('collapsed'); /* mark it as collapsed */
            }
            var currentfolder = '';
            if (escape( current.attr(rel_id) ) !== '/'){ currentfolder = current.attr( rel_id )};

            if (refreshtable){
                $( '#selected_file' ).text( "Current Folder: ROOT" + currentfolder);
                getcurrentdirectory(escape( current.attr(rel_id) ));
            }

        } else { /* clicked on file */
            $( '#selected_file' ).text( "File:  " + current.attr( rel_id ));
        }
    }
    $( '#FileTreeDisplay' ).on('click', 'LI', function() { /* monitor the click event on foldericon */
        var entry = $(this); /* get the parent element of the link */
        var current = $(this); /* get the parent element of the link */
        var id = 'id';
        clickfiletreedisplay(entry, current, id, false);
        return false;
    });

    $( '#FileTreeDisplay' ).on('click', 'LI A', function() { /* monitor the click event on links */
        var entry = $(this).parent(); /* get the parent element of the link */
        var current = $(this); /* get the parent element of the link */
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
    //@todo disabled untill ver4.0.1
    //disableradios();

    selectedids.forEach(function(entry) {

        if (entry.indexOf('dir:') === -1) {
            files += entry + ',  ';
            selectedvalues.push(entry);
        } else {
            folders += entry.replace('dir:', '') + ',  ';
            selectedvalues.push(entry.replace('dir:', ''));
        }

    });

    document.editpermform.chmodpaths.value = selectedvalues.join([separator = '{/@^}']); /*set dir value for ajax chmod call*/

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
	$('input:radio').attr('disabled', !recur.checked);
	$('input:radio').attr('checked', recur.checked);
	if (!recur.checked) {
		$('label[for=recurall]').css({color:'#A4A4A4'});
		$('label[for=recurfiles]').css({color:'#A4A4A4'});
		$('label[for=recurfolders]').css({color:'#A4A4A4'});

	}
	else {
		$('label[for=recurall]').css({color:'#000000'});
		$('label[for=recurfiles]').css({color:'#000000'});
		$('label[for=recurfolders]').css({color:'#000000'});
        document.getElementById("recurall").checked = true;
	}
	
	}
