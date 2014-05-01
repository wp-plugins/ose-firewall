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
