var controller = "dashboard";
Ext.ns('oseATH','oseATHINSTALLER');

function installDB () {
	var win = oseGetWIn('installer', 'Installer Information', 1024, 500); 
	win.show(); 
	win.update('Database installer preparing in progress');
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
				win.hide();
				location.reload(); 
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

var safeBrowsingWin = oseGetWIn('safeBrowsing', 'Checking Safe Browsing Status', 900, 650);

function checkSafebrowsing () {
	safeBrowsingWin.show(); 
	safeBrowsingWin.update('Checking Safe Browsing Status from our server, please allow a few minutes to complete.');
	checkSafebrowsingStatus (safeBrowsingWin);
}

function checkSafebrowsingStatus (win) {
	Ext.Ajax.request({
		url : url,
		params : {
			option : option,
			controller: controller,
			task: 'checkSafebrowsing',
			action: 'checkSafebrowsing'
		},
		method: 'POST',
		success: function ( response, options ) {
			var msg  = Ext.decode(response.responseText);
			if (msg.paid==false)
			{
				win.update('Your safebrowsing status is as follows: <br/>' + msg.safeBrowsingTable + '<br/><br/>' + msg.message + '<br/>' + msg.refund + '<br/>' + msg.form + '<br/>' + msg.form2  + '<br/>' + msg.form3 );
			}
			else
			{
				win.update('Your safebrowsing status is as follows: <br/>' + msg.safeBrowsingTable + '<br/><br/>' + msg.message);
			}
			updateSafebrowsingStatus (Ext.encode(msg.safeBrowsing));
		}
	});	
}

function updateSafebrowsingStatus (status) {
	Ext.Ajax.request({
		url : url,
		params : {
			option : option,
			controller: controller,
			task: 'updateSafebrowsingStatus',
			action: 'updateSafebrowsingStatus',
			status: status
		},
		method: 'POST',
		success: function ( response, options ) {
		}
	});	
}	
//tweet
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');

//facebook
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

//google+
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
  
  oseATHINSTALLER.form = Ext.create('Ext.form.Panel', {
		bodyStyle: 'padding: 10px; padding-left: 20px'
		,autoScroll: true
		,autoWidth: true
	    ,border: false
	    ,labelAlign: 'left'
	    ,labelWidth: 150
	    ,buttons: [
	    {
			text: 'Change now'
			,handler: function(){
				oseATHINSTALLER.form.getForm().submit({
					clientValidation: true,
					url : url,
					method: 'post',
					params:{
						option : option, 
						controller: controller, 
						task: 'changeusername',
						action: 'changeusername'
					},
					waitMsg: 'Please wait, this will take a few seconds ...',
					success: function(response, options){
						Ext.Msg.alert(options.result.status, options.result.result, function (btn) {
							if (btn =='ok')
							{
								Ext.getCmp('changeusername-win').close();
								location.reload();
							}
						});
					},
					failure:function(response, options){
						oseAjaxSuccessReload(options.result, 'alert', '', false);
					} 
					
				});
			}
		}
		]
	    ,items:[
	            oseGetNormalTextField('username', O_NEWUSERNAME, 100, 350, null),
	            oseGetDisplayField(O_NEWUSERNAME_NOTE)
	    ]
});
  
oseATHINSTALLER.win = new Ext.Window({
		title: 'Change administrator username'
		,id: 'changeusername-win'
		,modal: true
		,width: 600
		,border: false
		,autoHeight: true
		,closeAction:'hide'
		,items: [
		         oseATHINSTALLER.form
		]
		,closable: true
});	
  
function showForm () {
   oseATHINSTALLER.win.show().alignTo(Ext.getBody(),'t-t', [0, 50]);
}
  
Ext.getCmp('safeBrowsing').on ( 
	"close", function () {
		location.reload(); 
	}
)