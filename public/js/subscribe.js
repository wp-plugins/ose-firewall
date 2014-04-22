var controller = 'Versionupdate';
Ext.ns('oseATH','oseATHSubscribe');

oseATHSubscribe.simpleForm = Ext.create('Ext.form.Panel', {		
        frame: false,
        bodyStyle : 'padding:10px',
        labelAlign : 'top',
        renderTo: 'simple-form',
        height: 300,
        defaultType: 'textfield',
        items: [	oseGetNormalTextField('username', O_SUBSCRIPTION_USERNAME, 100, 350), 
                	oseGetNormalTextField('password', O_SUBSCRIPTION_PASSWORD, 100, 350)
                ],
        buttons: [{
            text: 'Save',			
			handler: function () {
				var form = oseATHSubscribe.simpleForm.getForm();
				var username = form.findField("username").getValue();
				var password = form.findField("password").getValue();
				if (username ===  "" || password === ""){
					Ext.Msg.alert('ERROR', O_PLS_ENTER_USERINFO);
					return false;
				}
				oseConfFormSubmit(oseATHSubscribe.simpleForm, url, option, controller, 'saveUserInfo','sub', O_PLEASE_WAIT);
			}
        },
        {
        	text: 'Change',
        	handler: function () {
        		var form = oseATHSubscribe.simpleForm.getForm();
				var username = form.findField("username").getValue();
				var password = form.findField("password").getValue();
				if (username ===  "" || password === ""){
					Ext.Msg.alert('ERROR', O_PLS_ENTER_USERINFO);
					return false;
				}
				oseConfFormSubmit(oseATHSubscribe.simpleForm, url, option, controller, 'changeUserInfo','sub', O_PLEASE_WAIT);
        	}
        },
        {
            text: 'Cancel',
			handler: function () {	
				oseATHSubscribe.simpleForm.getForm().reset();
			}
        }]
 
	});
