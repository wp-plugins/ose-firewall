var controller = "scanreport";
var option = "com_ose_firewall";

jQuery(document).ready(function($){
    $('#scanreportTable').dataTable( {
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: function ( d ) {
            	d.option = option;
                d.controller = controller;
                d.action = 'getMalwareMap';
                d.task = 'getMalwareMap';
                d.centnounce = $('#centnounce').val();
            }
        },
        columns: [
                { "data": "file_id" },
                { "data": "filename" },
                { "data": "patterns" },
                { "data": "pattern_id" },
                { "data": "confidence" },
                { "data": "view" },
                { "data": "checkbox" }
        ]
    } );
} );

function viewFiledetail(id)
{
	showLoading();
	jQuery(document).ready(function($){
		$('#filecontentModal').modal();
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:'viewfile',
		    		task:'viewfile',
		    		id:id,
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	           hideLoading();
	           $('#codeareaDiv').html(data.data);
	           var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('codearea'), {
	        	   lineNumbers: true,
	               matchBrackets: true,
	               mode: "application/x-httpd-php",
	               indentUnit: 4,
	               indentWithTabs: true
	           });
	        }
	      });
		
	});
}