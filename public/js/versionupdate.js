var controller = "Versionupdate";
var task = "createTables";

function updateVersion(){
	var win = oseGetWIn('update', 'Updating information', 500, 300);
	win.show();
	win.update('Starting download anti-virus database');
	createTables (0, win);
}

function createTables (step, win) {
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
			}
			else if (msg.status== 'Error'){
				win.update(msg.result);
			}
			else
			{
				if (msg.cont == 1)
				{	
					win.update(msg.result);
					createTables (msg.step, win);
				}
			}
		}
	});	
}









