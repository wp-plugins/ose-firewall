var controller = "vsscan";
	   
Ext.ns('oseATH','oseATHScanner');
oseATHScanner.pbar1 = oseGetProgressbar('pbar1', 'Database Initialisation Ready') ;
oseATHScanner.pbar2 = oseGetProgressbar('pbar2', 'Virus Scanning Ready') ;
oseATHScanner.pbar2.render ('progress-bar');
oseATHScanner.pbar2.updateProgress(0, totalFiles);

function vsScanButtonUpdate (status) {
	//Ext.get('vsscan').dom.disabled = status;
	//Ext.get('init').dom.disabled = status;
}

oseATHScanner.statusOption = new Array(
		   new Array(1, 'Basic Version'),
		   new Array(2, 'Advanced Version')
);

oseATHScanner.CheckSubWin = oseGetWIn('checkSubWin', 'Checking Subscription Status', 900, 650); 

oseATHScanner.updatePatternForm = Ext.create('Ext.form.Panel', {
	bodyStyle: 'padding: 10px; padding-left: 20px'
	,autoScroll: true
	,autoWidth: true
    ,border: false
    ,labelAlign: 'left'
    ,labelWidth: 150
    ,buttons: [
    {
		text: O_UPDATE_PATTERN,
		id: 'updatePatterns'
		,handler: function(){
    		oseATHScanner.updatePatternForm.submit({
	    		clientValidation: true,
	    		url : url,
	    		method: 'post',
	    		params:{
	    			option : option, 
	    			controller: controller, 
	    			task: 'updatePatterns',
	    			action: 'updatePatterns',
	    			centnounce: Ext.get('centnounce').getValue()
	    		},
	    		waitMsg: O_PLEASE_WAIT,
	    		success: function(response, options){
	    			var msg  = options.result;
	    			if (Ext.getCmp('patternType').value == 1)
	    			{
	    				oseATHScanner.CheckSubWin.height=300;
	    			}	
	    			Ext.getCmp('updatePatternWin').hide();
	    			
	    			oseATHScanner.CheckSubWin.show(); 
	    			oseATHScanner.CheckSubWin.update(msg.message  + '<br/>' + msg.refund + '<br/>' + msg.form + '<br/>' + msg.form2  + '<br/>' + msg.form3);
	    		},
	    		failure:function(response, options){
	    			var msg  = options.result;
	    			Ext.getCmp('updatePatternWin').hide();
	    			oseATHScanner.CheckSubWin.show(); 
	    			oseATHScanner.CheckSubWin.update(msg.message  + '<br/>' + msg.refund + '<br/>' + msg.form + '<br/>' + msg.form2  + '<br/>' + msg.form3);
	    		} 
	    	});
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
            oseGetCombo('patternType', O_UPDATE_PATTERN, oseATHScanner.statusOption, 400, 250, 100, 2)
    ]
});


oseATHScanner.updatePatternWin = new Ext.Window({
	title: O_UPDATE_PATTERN
	,id:'updatePatternWin'
	,modal: true
	,width: 500
	,border: false
	,autoHeight: true
	,closeAction:'hide'
	,items: [
	         oseATHScanner.updatePatternForm
	]
});	

function vsPatternUpdate () {
	oseATHScanner.updatePatternWin.show();  	
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

function scanAntivirus (step, task, counter) {
	Ext.Ajax.request({
		url : url,
		params : {
			option : option,
			controller: controller,
			task: task,
			action: task,
			step : step,
			centnounce: Ext.get('centnounce').getValue()
		},
		method: 'POST',
		success: function ( response, options ) {
			var msg  = Ext.decode(response.responseText);
			if (msg.showCountFiles == true )
			{
				Ext.Msg.show({
				    title: 'Total Files Count',
				    msg: msg.summary,
				    width: 600
				});    
				scanAntivirus (-1, task, 0);
			}	
			else
			{
				Ext.Msg.hide();
				
				if(msg.status == 'ERROR')
				{
					Ext.Msg.alert("Error", msg.result);
				}

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
						scanAntivirus (1, task, 0);
					}
					else
					{
						oseATHScanner.pbar2.updateProgress(0, O_VSSCAN_TERMINATED);
						vsScanButtonUpdate (false); 
					}	
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

Ext.get('vsscan').on('click', function(){
	oseATHScanner.pbar2.updateProgress(0, O_VSSCAN_INPROGRESS);
	vsScanButtonUpdate (true); 
	scanAntivirus (-2, 'vsscan');
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

Ext.getCmp('checkSubWin').on('close', function () {
	location.reload();
})
