var controller = "backup";
Ext.ns('oseATH','oseATHBACKUPMANAGER');
function deleteItem(id)
{
	Ext.Msg.confirm(O_DELETE_CONFIRM, O_DELETE_CONFIRM_DESC, function(btn, text){
		if (btn == 'yes'){
			oseDeleteItem(url, option, controller, 'deleteItemByID', id , oseATHBACKUPMANAGER.store);
		}
	});
}
function Countdown(options) {
	  var timer,
	  instance = this,
	  seconds = options.seconds || 10,
	  updateStatus = options.onUpdateStatus || function () {},
	  counterEnd = options.onCounterEnd || function () {};
	  function decrementCounter() {
	    updateStatus(seconds);
	    if (seconds === 0) {
	      counterEnd();
	      instance.stop();
	    }
	    seconds--;
	  }

	  this.start = function () {
	    clearInterval(timer);
	    timer = 0;
	    seconds = options.seconds;
	    timer = setInterval(decrementCounter, 1000);
	  };

	  this.stop = function () {
	    clearInterval(timer);
	  };
}
function downloadDB(ids)
{
	oseAjaxTaskRequestWithIDS('oseATHBACKUPMANAGER', url, option, controller, 'downloadBackupDB', ids);
}

function downloadFile(ids)
{
	oseAjaxTaskRequestWithIDS('oseATHBACKUPMANAGER', url, option, controller, 'downloadBackupFile', ids);
}
function bkFormSubmit(form, url, option, controller, task, store, waitMsg) 
{
	form.getForm().submit({
		clientValidation: true,
		url : url,
		method: 'post',
		params:{
			option : option, 
			controller: controller, 
			task: task,
			action: task,
			centnounce: Ext.get('centnounce').getValue()
		},
		waitMsg: waitMsg,
		success: function(response, options){
			var msg  = Ext.decode(response.responseText);
			if(options.result.cont == true)
			{
				backupFiles (Ext.getCmp('backup_type').getValue());  
			}else{
				oseAjaxSuccessReload(options.result,  'show',  store, true);
			}	
		},
		failure:function(response, options){
			oseAjaxSuccessReload(options.result, 'alert', store, true);
		} 
		
	});
}
function backupFiles (backupType) {
	if(backupType)
	oseATHBACKUPMANAGER.fileBackupWin.show(); 
	backupFilesAjax (-1, oseATHBACKUPMANAGER.fileBackupWin, 'backupFile' , backupType, 0); 
}	
function backupFilesAjax (step, win, task, backupType, counter) {
	Ext.Ajax.request({
		url : url,
		params : {
			backup_type : backupType,
			option : option,
			controller: controller,
			task: task,
			action: task,
			step : step,
			centnounce: Ext.get('centnounce').getValue()
		},
		method: 'POST',
		success: function ( response, options ) {
				if (msg.status=='Completed')
				{
					win.update(msg.result);
					//win.hide();
				}
				else
				{
					if(step >= 1 || step < 0){
						step=0;
					}
					oseATHBACKUPMANAGER.pbar1.updateProgress(step, msg.summary);
					Ext.fly('scanned_files').update(msg.lastscanned);
					if (msg.cont > 0)
					{	
						backupFilesAjax (step+0.1, win, task, backupType, 0);
					}
					else
					{
						oseATHBACKUPMANAGER.pbar1.updateProgress(1, O_BACKUP_FILE_COMPLETE);
					}	
				}
		},
		failure : function ( request, status ) {
			if (request.timedout==true)
			{
				counter = 0;

				var myCounter = new Countdown({  
					seconds:1,  // number of seconds to count down
				    onUpdateStatus: function(sec){
				    },  // callback for each second
				    onCounterEnd: function(){ 
				    	backupFilesAjax (step+0.1, win, task, backupType, 0);
	                } // final action
				});
				myCounter.start();
			}
			
		}
	});	
}
function checkAuth(url, option, controller, backup_to)
{
	Ext.Ajax.request({
		url : url,
		params : {
			backup_to : backup_to,
			option : option,
			controller: controller,
			task: 'checkAuth',
			action: 'checkAuth',
			centnounce: Ext.get('centnounce').getValue()
		},
		method: 'POST',
		success: function ( response, options ) {
			var msg  = Ext.decode(response.responseText);
			if(msg.dbReady == false)
			{
				saveAppAccess();
			}
			else
			{
				if(msg.tokenReady == false)
				{
					var x = screen.width/2 - 700/2;
				    var y = screen.height/2 - 450/2;
					window.open(msg.authurl, 'Authorise Dropbox', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=700, height=485, top='+y+', left='+x);
				}
				if(msg.error == true)
				{
					Ext.Msg.alert("Error", msg.message);
				}
			}
		},
		failure:function(response, options){
			
		} 
	});
}

oseATHBACKUPMANAGER.blurListener = oseGetIPBlurListener(); 
oseATHBACKUPMANAGER.fields = new Array('id', 'server', 'type', 'date', 'restore', 'dbBackupPath',  'delete');
oseATHBACKUPMANAGER.store = oseGetStore('attacksum', oseATHBACKUPMANAGER.fields, url, option, controller, 'getBackupList');
oseATHBACKUPMANAGER.form = Ext.create('Ext.form.Panel', {
		bodyStyle: 'padding: 10px; padding-left: 20px'
		,autoScroll: true
		,autoWidth: true
	    ,border: false
	    ,labelAlign: 'left'
	    ,labelWidth: 150
	    ,buttons: [
	    {
			text: 'Backup Now'
			,handler: function(){	
				if (Ext.getCmp('backup_type').getValue()=="")
				{
					Ext.Msg.alert("Error", "Please select a backup type");
					return false; 
				}
				if(Ext.getCmp('backup_to').getValue() != 1)
				{
					checkAuth(url, option, controller ,Ext.getCmp('backup_to').getValue());
				}
				else
				{
					if (Ext.getCmp('backup_type').getValue() == 1)
					{
						backupFiles (Ext.getCmp('backup_type').getValue()); 
					}	
					else
					{
						bkFormSubmit(oseATHBACKUPMANAGER.form, url, option, controller, 'backup', oseATHBACKUPMANAGER.store, 'Please wait, this will take a few seconds ...');
					}	
				}
			}
		}
		]
	    ,items:[
	        {
			   	xtype:'combo'
				,fieldLabel: O_BACKUP_TYPE
				,hiddenName: 'backup_type'
				,id: 'backup_type'
				,name: 'backup_type'
				,typeAhead: true
				,triggerAction: 'all'
				,lazyRender:false
				,width: 300
				,labelWidth: 150
				,mode: 'local'
				,store: Ext.create('Ext.data.ArrayStore', {
					    fields: [
					       'value',
					       'text'
					    ],
					    data: [
					      	//[1, "File ONLY"],
					      	[2, "Database ONLY"]
					      	//[3, "File & Database"]
						]
					})
					,valueField: 'value'
					,displayField: 'text'
					,listeners:{
						render:{
							fn:function(combo, value) {
    							combo.setValue(2);
						    }
						}
					}						
			}
			/*
			,{
			   	xtype:'combo'
				,fieldLabel: O_BACKUP_TO
				,hiddenName: 'backup_to'
				,id: 'backup_to'
				,name: 'backup_to'
				,typeAhead: true
				,triggerAction: 'all'
				,lazyRender:false
				,width: 300
				,labelWidth: 150
				,mode: 'local'
				,store: Ext.create('Ext.data.ArrayStore', {
					    fields: [
					       'value',
					       'text'
					    ],
					    data: [
					      	[1, "Local"],
					      	[2, "Dropbox"]
						]
					})
					,valueField: 'value'
					,displayField: 'text'
					,listeners:{
						render:{
							fn:function(combo, value) {
								combo.setValue(1);
						    }
						}
					}						
			}
			*/
	    ]
});

function saveAccessInfo(form, url, option, controller, task, store)
{
	form.getForm().submit({
		clientValidation: true,
		url : url,
		method: 'post',
		params:{
			option : option, 
			controller: controller, 
			task: task,
			action: task,
			centnounce: Ext.get('centnounce').getValue()
		},
		success: function(response, options){
			Ext.Msg.alert("Success", "Save information success", function(btn, text){
				if (btn == 'ok'){
					oseATHBACKUPMANAGER.win2.close();
					Ext.getCmp("backupButtonwin").show();
				}
				else
				{
					oseATHBACKUPMANAGER.win2.close();
				}
			});
		},
		failure:function(response, options){
			oseAjaxSuccessReload(options.result, 'alert', store, true);
		} 
		
	});
}

oseATHBACKUPMANAGER.accessBackupInfoForm = Ext.create('Ext.form.Panel', {
	bodyStyle : 'padding: 10px; padding-left: 20px',
	autoScroll : true,
	autoWidth : true,
	border : false,
	labelAlign : 'left',
	labelWidth : 150,
	buttons : [ {
		text : 'Save',
		handler : function() {
			if(Ext.getCmp('access_username').getValue()== "" || Ext.getCmp('access_password').getValue() == "")
			{
				Ext.Msg.alert("Error", "App key or App secret cannot be null");
				return false; 
			}
				saveAccessInfo(oseATHBACKUPMANAGER.accessBackupInfoForm, url, option, controller, 'saveAppAccess', oseATHBACKUPMANAGER.store, 'Please wait, this will take a few seconds ...');
		}
	} ],
	items : [ 
	          oseGetNormalTextField('access_username', 'App key', 100, 450, null, false), 
	          oseGetNormalPassword('access_password', 'App secret', 100, 450, null, false) ]
});

function saveAppAccess()
{
	Ext.getCmp("backupButtonwin").hide();
	oseATHBACKUPMANAGER.win2 = oseGetWIn('addAccessWin', 'Add your app access');
	oseATHBACKUPMANAGER.win2.add(oseATHBACKUPMANAGER.accessBackupInfoForm);
	oseATHBACKUPMANAGER.win2.show().alignTo(Ext.getBody(), 't-t', [ 0, 50 ]);
	
}

oseATHBACKUPMANAGER.pbar1 = oseGetProgressbar('pbar1', 'File Backup Ready') ;
oseATHBACKUPMANAGER.fileBackupForm = Ext.create('Ext.form.Panel', {
	bodyStyle: 'padding: 10px; padding-left: 20px'
	,autoScroll: true
	,autoWidth: true
    ,border: false
    ,labelAlign: 'left'
    ,labelWidth: 150
    ,buttons: [
    {
		text: O_STOP,
		id: 'stopdbbutton'
		,handler: function(){
			
		}
	},
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
			html: '<div id ="scanned_files">&nbsp;</div>'
		},
		oseATHBACKUPMANAGER.pbar1
    ]
});

oseATHBACKUPMANAGER.fileBackupWin = new Ext.Window({
	title: O_FILE_BACKUP
	,modal: true
	,width: 600
	,border: false
	,autoHeight: true
	,closeAction:'hide'
	,items: [
	         oseATHBACKUPMANAGER.fileBackupForm
	]
});	

oseATHBACKUPMANAGER.panel = Ext.create('Ext.grid.Panel', {
		id: 'oseATHBACKUPMANAGERPanel',
		name: 'oseATHBACKUPMANAGERPanel',
	    store: oseATHBACKUPMANAGER.store,
	    selType: 'rowmodel',
	    multiSelect: true,
	    columns: [
            {id: 'id', header: O_ID,  hidden:false, dataIndex: 'id', width: "5%", sortable: true}
            ,{id: 'server', header: 'Server',  hidden:false, dataIndex: 'server', width: "20%",  sortable: true}
            ,{id: 'type', header: 'Backup type',  hidden:false, dataIndex: 'type', width: "20%",  sortable: true}
            ,{id: 'datetime', header: 'Date',  hidden:false, dataIndex: 'date',width: "20%", sortable: true}
            ,{id: 'dbBackupPath', header: 'DataBase Download',  hidden:false, dataIndex: 'dbBackupPath',width: "25%", sortable: true}
            //,{id: 'restore', header: '',  hidden:false, dataIndex: 'restore', width: 80, sortable: true}
           
            //,{id: 'fileBackupPath', header: 'File Download',  hidden:false, dataIndex: 'fileBackupPath', width: "20%", sortable: true} 
            ,{id: 'delete', header: 'Delete',  hidden:false, dataIndex: 'delete', width: "9.5%", sortable: true}
	    ],
	    sortInfo:{field: 'datetime', direction: "DESC"},
	    height: 500,
	    width: '100%',
	    renderTo: 'oseantihackerBackupManager',
	    tbar: new Ext.Toolbar({
			defaults: {bodyStyle:'border:0px solid transparent;'},
			items: [
				    	oseGetAddWinButton('backupButton', O_BACKUP, O_BACKUP, oseATHBACKUPMANAGER.form, 600),
				        {
				        	id: 'delSelected',
				            text: O_DELETE_ITEMS,
				            handler: function(){
				             	osePanelButtonAction(O_DELETE_CONFIRM, 
				             						 O_DELETE_CONFIRM_DESC, 
				             						 oseATHBACKUPMANAGER.panel, oseATHBACKUPMANAGER, url, option, controller, 
				             						 'deleteBackup'
				             	);
				            }
				        },
				        '->'
				    ]
		}),
		bbar: oseGetPaginator(oseATHBACKUPMANAGER)
});