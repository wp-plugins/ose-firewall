var controller = "dashboard";
Ext.ns('oseATH','oseATHINSTALLER');

oseATHINSTALLER.panel = 
	Ext.create('Ext.panel.Panel', {	
	renderTo: 'container-right',
    title : 'Like & Follow Centrora',
    width : 300,
    height: 400,
    layout: 'fit',
    bodyStyle: 'padding:5px',
    items: [
        {
            xtype: 'panel',
            border: false,
            layout: {
                type: 'vbox',
                align: 'stretch'
            },
            items: [
                {
                    html: getShareLinks(),
                    height:100,
                    margin: '0 0 0 0'
                },{
                    xtype: 'panel',
                    title: 'Write a review on WordPress',
                    html: '<a href = "http://wordpress.org/support/view/plugin-reviews/ose-firewall"><i class="fa fa-pencil"></i>Click here to write a review</a>',
                    flex: 1
                }
            ]
        }
    ]
});

function getShareLinks(){
	var	link = '<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fprotectwebsite&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=true&amp;share=false&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>';
		link += '<p><a href="https://twitter.com/ProtectWebsite" class="twitter-follow-button"  data-size="small" data-show-count="true" data-show-screen-name="false"></a>';
		link += '<div class="g-follow" data-annotation="bubble" data-height="20" data-href="https://plus.google.com/100825419799499224939" data-rel="publisher"></div>';
	return link;
}


function getVisitLinks(){
	/*var link = '<p><a class="fb-like" data-href="https://www.facebook.com/protectwebsite" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></a></p>';
	link += '<p><a class="obtn-small obtn-blueDark" href="https://twitter.com/ProtectWebsite" target="_blank"><i class="icon-twitter"></i> Twitter</a>';
	link += '<p><a class="obtn-small obtn-orange" href="https://plus.google.com/u/0/100825419799499224939/posts" target="_blank"><i class="icon-googleplus	"></i> Google+</a>';
	return link;*/
}
	   
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
