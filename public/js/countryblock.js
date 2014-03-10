var controller = "countryblock";
var downloadFinish = false;

Ext.ns('oseATH','oseATHCOUNTRYBLOCKER');
oseATHCOUNTRYBLOCKER.pbar1 = oseGetProgressbar('pbar1', 'Database Initialisation Ready') ;
function changeItemStatus(id, status)
{
	Ext.Msg.confirm(O_CHANGE_IP_STATUS, O_CHANGE_IP_STATUS_DESC, function(btn, text){
		if (btn == 'yes'){
			oseChangeItemStatus(url, option, controller, 'changeCountryStatus', id, status , oseATHCOUNTRYBLOCKER.store);
		}
	});
}

function downLoadDB(){
	oseATHCOUNTRYBLOCKER.downloadDBWin.show();
	downLoadFile(8, oseATHCOUNTRYBLOCKER.downloadDBWin, "downLoadTables");
}
function installDB () {
	var win = oseGetWIn('installer', 'Installer Information', 1024, 500); 
	win.show(); 
	win.update('Database installer preparing in progress');
	createTables (0, win, "createTables");
}

function createTables (step, win, task) {
	Ext.Ajax.request({
		url : url,
		params : {
			option : option,
			controller: controller,
			task: task,
			action: task,
			step : step
		},
		method: 'POST',
		success: function ( response, options ) {
			var msg  = Ext.decode(response.responseText);
			if (msg.status=='Completed')
			{
				win.update(msg.result);
				win.hide();
				location.reload(); 
			}
			else
			{
				if (msg.cont == 1)
				{	
					win.update(msg.result);
					createTables (msg.step, win, task);
				}
			}
		}
	});	
}

function downLoadFile(step, win, task) {
	Ext.Ajax.request({
		url : url,
		params : {
			option : option,
			controller: controller,
			task: task,
			action: task,
			step : step
		},
		method: 'POST',
		success: function (response, options) {
			var msg  = Ext.decode(response.responseText);
			if (msg.status=='Completed')
			{
				downloadFinish = true;
				oseATHCOUNTRYBLOCKER.pbar1.updateProgress(1, msg.result);
				win.hide();
				installDB();
				//location.reload(); 
			}
			else
			{
				oseATHCOUNTRYBLOCKER.downloadDBForm
				oseATHCOUNTRYBLOCKER.pbar1.updateProgress(1.0/step, msg.result);	
				downLoadFile(step-1, win, task);
			}
		},
		failure: function(response, options) {
            Ext.MessageBox.show({
                title: 'Message',
                msg: 'fail',
                buttons: Ext.MessageBox.OK
           });
}
	});	
}

oseATHCOUNTRYBLOCKER.downloadDBForm = Ext.create('Ext.form.Panel', {
	bodyStyle: 'padding: 10px; padding-left: 20px'
	,autoScroll: true
	,autoWidth: true
    ,border: false
    ,labelAlign: 'left'
    ,labelWidth: 150
    ,buttons: [
    {
		text: O_STOP,
		id: 'stopvsscanbutton'
		,handler: function(){
			oseATHCOUNTRYBLOCKER.pbar1.updateProgress(0, O_DOWNLOAD_TERMINATED);
			vsScanButtonUpdate (false); 
			Ext.Ajax.abort(); 
		}
	}
	,
	{
		text: O_CLOSE,
		id: 'closebutton'
		,handler: function(){
			location.reload();  
		}
	}
	]
    ,items:[
		{
			html: '<div id ="scan_progress">&nbsp;</div>'
		},
		oseATHCOUNTRYBLOCKER.pbar1,
		{
			html: '<div id ="last_file">&nbsp;</div>'
		}
    ]
});


oseATHCOUNTRYBLOCKER.blurListener = oseGetIPBlurListener(); 
oseATHCOUNTRYBLOCKER.statusOption = new Array(
					   new Array(1, 'Blacklisted'), 
					   new Array(3, 'Whitelisted')
);
oseATHCOUNTRYBLOCKER.fields = new Array('country_code', 'id', 'name', 'status');
oseATHCOUNTRYBLOCKER.store = oseGetStore('attacksum', oseATHCOUNTRYBLOCKER.fields, url, option, controller, 'getCountryList');
oseATHCOUNTRYBLOCKER.downloadDBWin = new Ext.Window({
	title: O_DOWNLOAD_FILES
	,modal: true
	,width: 800
	,border: false
	,autoHeight: true
	,closeAction:'hide'
	,items: [
	      oseATHCOUNTRYBLOCKER.downloadDBForm
	]
});	

oseATHCOUNTRYBLOCKER.downloadDBWin.on ('close', function () {
	location.reload(); 
})

oseATHCOUNTRYBLOCKER.panel = Ext.create('Ext.grid.Panel', {
		id: 'oseATHCOUNTRYBLOCKERPanel',
		name: 'oseATHCOUNTRYBLOCKERPanel',
	    store: oseATHCOUNTRYBLOCKER.store,
	    selType: 'rowmodel',
	    multiSelect: true,
	    columns: [
	        {id: 'country_code', header: '',  hidden:false, dataIndex: 'country_code', width: 30, sortable: true}
            ,{id: 'id', header: O_ID,  hidden:false, dataIndex: 'id', width: 40, sortable: true}
            ,{id: 'name', header: O_IP_RULE_TITLE,  hidden:false, dataIndex: 'name', width: 130,  sortable: true}
            ,{id: 'status', header: O_STATUS,  hidden:false, dataIndex: 'status', sortable: true}
	    ],
	    sortInfo:{field: 'name', direction: "ASC"},
	    height: 500,
	    width: '100%',
	    renderTo: 'oseantihackerIPManager',
	    tbar: new Ext.Toolbar({
			defaults: {bodyStyle:'border:0px solid transparent;'},
			items: [
				        {
				        	id: 'blkSelected',
				            text: O_BLACKLIST_IP,
				            handler: function(){			
				            	osePanelButtonAction(O_BLACKLIST_CONFIRM, 
				            						 O_BLACKLIST_CONFIRM_DESC, 
				             						 oseATHCOUNTRYBLOCKER.panel, oseATHCOUNTRYBLOCKER, url, option, controller,  
				             						 'blacklistIP'
				             	);
				            }
				        },{
				        	id: 'whtSelected',
				            text: O_WHITELIST_IP,
				            handler: function(){			
				            	osePanelButtonAction(O_WHITELIST_CONFIRM, 
				            						 O_WHITELIST_CONFIRM_DESC, 
				             						 oseATHCOUNTRYBLOCKER.panel, oseATHCOUNTRYBLOCKER, url, option, controller, 
				             						 'whitelistIP'
				             	);
				             	
				            }
				        },
				        '->',
				        oseGetStatusFilter(oseATHCOUNTRYBLOCKER)
				        ,'-',
				        oseGetSearchField (oseATHCOUNTRYBLOCKER)
				    ]
		}),
		bbar: oseGetPaginator(oseATHCOUNTRYBLOCKER)
});