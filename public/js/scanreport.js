var controller = "scanreport";

Ext.ns('oseATH','oseATHVSREPORT');
function oseGetAjaxSuccessWin (msg, win)
{
	if (msg.status!='ERROR')
	{
		win.show();
		win.update(msg.results);
	}
	else
	{
		Ext.Msg.alert(msg.status, msg.result);
	}
}

function viewFiledetail(id)
{
	var Viewwin = new Ext.Window({
		id: 'viewFile',
		name: 'viewFile',
		title: 'viewFile',
        width: 1024,
        height: 500,
        closeAction:'destroy',
        autoScroll:true
	}); 
	
	Ext.Ajax.request({
		url : url,
		params : {
			option : option,
			controller: controller,
			task: 'viewfile' ,
			action: 'viewfile' ,
			id: id
		},
		method: 'POST',
		success: function ( response, options ) {
			oseGetAjaxSuccessWin (Ext.decode(response.responseText), Viewwin);
		}
	});
}
oseATHVSREPORT.vstypeFields = new Array('id', 'title');
oseATHVSREPORT.vstypeStore = oseGetStore('vstypeStore', oseATHVSREPORT.vstypeFields, url, option, controller, 'getTypeList', params = '');

oseATHVSREPORT.fields = new Array('file_id', 'filename', 'type', 'confidence','view');
oseATHVSREPORT.store = oseGetStore('attacksum', oseATHVSREPORT.fields, url, option, controller, 'getMalwareMap');

oseATHVSREPORT.panel = Ext.create('Ext.grid.Panel', {
	id: 'oseATHVSREPORTPanel',
	name: 'oseATHVSREPORTPanel',
    store: oseATHVSREPORT.store,
    selType: 'rowmodel',
    multiSelect: true,
    columns: [
        {id: 'file_id', header: O_FILE_ID,  hidden:false, dataIndex: 'file_id', width: 80, sortable: true}
        ,{id: 'filename', header: O_FILE_NAME,  hidden:false, dataIndex: 'filename',width: '80%', sortable: true}
        ,{id: 'confidence', header: O_CONFIDENCE,  hidden:false, dataIndex: 'confidence', width: '7%',  sortable: true}
        ,{id: 'view', header: '',  hidden:false, dataIndex: 'view', width: '3%',  sortable: true}
    ],
    sortInfo:{field: 'filename', direction: "ASC"},
    height: 735,
    width: '100%',
    renderTo: 'oseantivirusScanReport',
    tbar: new Ext.Toolbar({
		defaults: {bodyStyle:'border:0px solid transparent;'},
		items: [
			    	'->','-',
			        oseGetSearchField (oseATHVSREPORT)
			    ]
	}),
	bbar: oseGetPaginator(oseATHVSREPORT)
});
