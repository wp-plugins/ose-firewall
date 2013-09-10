var controller = "vsscan";
	   
Ext.ns('oseATH','oseATHScanner');
oseATHScanner.pbar1 = oseGetProgressbar('pbar1', 'Database Initialisation Ready') ;
oseATHScanner.pbar2 = oseGetProgressbar('pbar2', 'Virus Scanning Ready') ;

function initDBButtonUpdate (status) {
	Ext.getCmp('path').setDisabled(status);
	Ext.getCmp('initdbbutton').setDisabled(status);
}

function vsScanButtonUpdate (status) {
	Ext.getCmp('vsscanbutton').setDisabled(status);
	Ext.getCmp('vsscancontinue').setDisabled(status);
}

oseATHScanner.initDBForm = Ext.create('Ext.form.Panel', {
	bodyStyle: 'padding: 10px; padding-left: 20px'
	,autoScroll: true
	,autoWidth: true
    ,border: false
    ,labelAlign: 'left'
    ,labelWidth: 150
    ,buttons: [
    {
		text: O_INIT_DATABASE,
		id: 'initdbbutton'
		,handler: function(){
			oseATHScanner.pbar1.updateProgress(0, O_INITDB_INPROGRESS);
			initDBButtonUpdate (true); 
			initDatabase(-1, oseATHScanner.InitDBWin, 'initDatabase'); 
		}
	},
    {
		text: O_STOP,
		id: 'stopdbbutton'
		,handler: function(){
			oseATHScanner.pbar1.updateProgress(0, O_INITDB_TERMINATED);
			initDBButtonUpdate (false); 
			Ext.Ajax.abort(); 
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
		oseGetNormalTextField('path', O_PLEASE_ENTER_A_PATH, 200, 650),
		{
			html: '<div id ="scanned_files">&nbsp;</div>'
		},
		oseATHScanner.pbar1
    ]
});

oseATHScanner.vsScanform = Ext.create('Ext.form.Panel', {
	bodyStyle: 'padding: 10px; padding-left: 20px'
	,autoScroll: true
	,autoWidth: true
    ,border: false
    ,labelAlign: 'left'
    ,labelWidth: 150
    ,buttons: [
    {
		text: O_SCAN_VIRUS,
		id: 'vsscanbutton'
		,handler: function(){
			oseATHScanner.pbar2.updateProgress(0, O_VSSCAN_INPROGRESS);
			vsScanButtonUpdate (true); 
			scanAntivirus (-1, 'vsscan');
		}
	},
	{
		text: O_SCAN_VIRUS_CONTINUE,
		id: 'vsscancontinue'
		,handler: function(){
			vsScanButtonUpdate (true); 
			scanAntivirus (1, 'vsscan');
		}
	},
    {
		text: O_STOP,
		id: 'stopvsscanbutton'
		,handler: function(){
			oseATHScanner.pbar2.updateProgress(0, O_VSSCAN_TERMINATED);
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
		oseATHScanner.pbar2,
		{
			html: '<div id ="last_file">&nbsp;</div>'
		}
    ]
});

function initDatabase (step, win, task) {
	Ext.Ajax.request({
		url : url,
		params : {
			option : option,
			controller: controller,
			task: task,
			action: task,
			step : step,
			path : Ext.getCmp('path').getValue()
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
				oseATHScanner.pbar1.updateProgress(1, msg.summary);
				Ext.fly('scanned_files').update(msg.lastscanned);
				if (msg.cont > 0)
				{	
					initDatabase (1, win, task);
				}
				else
				{
					oseATHScanner.pbar1.updateProgress(0, O_INITDB_COMPLETED);
					initDBButtonUpdate (false);
				}	
			}
		}
	});	
}

function scanAntivirus (step, task) {
	Ext.Ajax.request({
		url : url,
		params : {
			option : option,
			controller: controller,
			task: task,
			action: task,
			step : step,
			path : Ext.getCmp('path').getValue()
		},
		method: 'POST',
		success: function ( response, options ) {
			var msg  = Ext.decode(response.responseText);
			if (msg.status=='Completed')
			{
				oseATHScanner.pbar2.updateProgress(msg.completed, msg.summary);
				Ext.Msg.show({
				    title: msg.status,
				    msg: O_VSSCAN_COMPLETED,
				    width: 300,
				    buttons: Ext.Msg.OK,
				    fn: function() { location.reload(); }
				});
			}
			else
			{
				oseATHScanner.form2
				oseATHScanner.pbar2.updateProgress(msg.completed, msg.summary);
				Ext.fly('scan_progress').update(msg.progress);
				Ext.fly('last_file').update(msg.last_file);
				if (msg.cont > 0)
				{	
					scanAntivirus (1, task);
				}
				else
				{
					oseATHScanner.pbar2.updateProgress(0, O_VSSCAN_TERMINATED);
					vsScanButtonUpdate (false); 
				}	
			}
		}
	});	
}

oseATHScanner.InitDBWin = new Ext.Window({
	title: O_SCAN_PATH
	,modal: true
	,width: 800
	,border: false
	,autoHeight: true
	,closeAction:'hide'
	,items: [
	      oseATHScanner.initDBForm
	]
});	

oseATHScanner.scanDBWin = new Ext.Window({
	title: O_VIRUS_SCANNING
	,modal: true
	,width: 800
	,border: false
	,autoHeight: true
	,closeAction:'hide'
	,items: [
	       oseATHScanner.vsScanform
	]
});	

oseATHScanner.InitDBWin.on ('close', function () {
	location.reload(); 
})

oseATHScanner.scanDBWin.on ('close', function () {
		location.reload(); 
})

Ext.get('init').on('click', function(){
	Ext.fly('scannedInfo').update(DB_INITIALIZATION_IN_PROGRESS);
	oseATHScanner.InitDBWin.show();
	Ext.getCmp('path').setValue(path);
});

Ext.get('vsscan').on('click', function(){
	Ext.fly('scannedInfo').update(VIRUS_SCANNING_IN_PROGRESS);
	oseATHScanner.scanDBWin.show();
});