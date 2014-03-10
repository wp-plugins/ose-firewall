var controller = "vsscan";
	   
Ext.ns('oseATH','oseATHScanner');
oseATHScanner.pbar1 = oseGetProgressbar('pbar1', 'Database Initialisation Ready') ;
oseATHScanner.pbar2 = oseGetProgressbar('pbar2', 'Virus Scanning Ready') ;
oseATHScanner.pbar2.render ('progress-bar');
oseATHScanner.pbar2.updateProgress(0, totalFiles);

function initDBButtonUpdate (status) {
	Ext.getCmp('path').setDisabled(status);
	Ext.getCmp('initdbbutton').setDisabled(status);
}

function vsScanButtonUpdate (status) {
	//Ext.get('vsscan').dom.disabled = status;
	//Ext.get('init').dom.disabled = status;
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
			initDatabase(-1, oseATHScanner.InitDBWin, 'initDatabase', 0); 
		}
	},
	{
		text: O_CONTINUE,
		id: 'contdbbutton'
		,handler: function(){
			oseATHScanner.pbar1.updateProgress(0, O_INITDB_INPROGRESS);
			initDBButtonUpdate (true); 
			initDatabase(0, oseATHScanner.InitDBWin, 'initDatabase', 0); 
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

function initDatabase (step, win, task, counter) {
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
				if(step >= 1 || step < 0){
					step=0;
				}
				oseATHScanner.pbar1.updateProgress(step, msg.summary);
				Ext.fly('scanned_files').update(msg.lastscanned);
				if (msg.cont > 0)
				{	
					/*if (counter < 15) 
					{
						counter ++;
						initDatabase (step+0.1, win, task, counter); 
					}
					else
					{
						counter = 0;
						Ext.MessageBox.show({ 
							msg: "Let's give the server a rest", 
							progressText: 'Wait...', 
							width:300, 
							wait:true
						}); 

						var myCounter = new Countdown({  
						    seconds:5,  // number of seconds to count down
						    onUpdateStatus: function(sec){
								Ext.MessageBox.updateProgress(0.2, sec + ' seconds left...',sec + ' seconds left...');
									
						    }, // callback for each second
						    onCounterEnd: function(){ 
						    	Ext.MessageBox.close(); */
						    	initDatabase (step+0.1, win, task, 0); 
	                     /*   } // final action
						});
						
						myCounter.start(); 
					}	 */

				}
				else
				{
					oseATHScanner.pbar1.updateProgress(1, O_INITDB_COMPLETED);
					initDBButtonUpdate (false);
				}	
			}
		},
		failure : function ( request, status ) {
			if (request.timedout==true)
			{
				counter = 0;
				/*Ext.MessageBox.show({ 
					msg: "Server respond aborted message, let's wait for a while and continue later", 
					progressText: 'Wait...', 
					width:300, 
					wait:true
				}); */

				var myCounter = new Countdown({  
					seconds:1,  // number of seconds to count down
				    onUpdateStatus: function(sec){
						//Ext.MessageBox.updateProgress(0.05, sec + ' seconds left...',sec + ' seconds left...');
				    },  // callback for each second
				    onCounterEnd: function(){ 
				    	//Ext.MessageBox.close(); 
				    	initDatabase(0, oseATHScanner.InitDBWin, 'initDatabase', 0); 
	                	    } // final action
				});
				myCounter.start();
			}
			
		}
	});	
}

function scanAntivirus (step, task, counter) {
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
					/*if (counter < 15) 
					{
						counter ++;
						scanAntivirus (1, task, counter);
					}	
					else
					{
						counter = 0;
						Ext.MessageBox.show({ 
							msg: "Let's give the server a rest", 
							progressText: 'Wait...', 
							width:300, 
							wait:true
						}); 

						var myCounter = new Countdown({  
						    seconds:5,  // number of seconds to count down
						    onUpdateStatus: function(sec){
								Ext.MessageBox.updateProgress(0.05, sec + ' seconds left...', 'Server respond aborted message, let\'s wait for a while and continue later');
						    }, // callback for each second
						    onCounterEnd: function(){ 
						    	Ext.MessageBox.close(); */
						    	scanAntivirus (1, task, 0);
	                       /* } // final action
						});
						
						myCounter.start(); 
					}*/
				}
				else
				{
					oseATHScanner.pbar2.updateProgress(0, O_VSSCAN_TERMINATED);
					vsScanButtonUpdate (false); 
				}	
			}
		},
		failure : function ( request, status ) {
			if (request.timedout==true)
			{
				counter = 0;
				/*Ext.MessageBox.show({ 
					msg: "Server respond aborted message, let's wait for a while and continue later", 
					progressText: 'Wait...', 
					width:300, 
					wait:true
				}); */

				var myCounter = new Countdown({  
					seconds:1,  // number of seconds to count down
				    onUpdateStatus: function(sec){
						//Ext.MessageBox.updateProgress(0.05, sec + ' seconds left...', 'Server respond aborted message, let\'s wait for a while and continue later');							
				    },  // callback for each second
				    onCounterEnd: function(){ 
				    	//Ext.MessageBox.close(); 
				    	scanAntivirus (1, task, 0); 
				    } // final action
				});
				myCounter.start();
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

Ext.get('init').on('click', function(){
	oseATHScanner.pbar2.updateProgress(0, DB_INITIALIZATION_IN_PROGRESS);
	oseATHScanner.InitDBWin.show();
	Ext.getCmp('path').setValue(path);
});

Ext.get('vsscan').on('click', function(){
	oseATHScanner.pbar2.updateProgress(0, O_VSSCAN_INPROGRESS);
	vsScanButtonUpdate (true); 
	scanAntivirus (-1, 'vsscan');
});

Ext.get('vsstop').on('click', function(){
	oseATHScanner.pbar2.updateProgress(0, O_VSSCAN_TERMINATED);
	vsScanButtonUpdate (false); 
	Ext.Ajax.abort(); 
});

Ext.get('vscont').on('click', function(){
	oseATHScanner.pbar2.updateProgress(0, O_CONTINUE_SCAN);
	vsScanButtonUpdate (true); 
	scanAntivirus (1, 'vsscan');
});
