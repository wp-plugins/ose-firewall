var controller = "avconfig";

Ext.ns('oseATH','oseConfAV');
oseConfAV.Option = oseGetYesORNoOptions(); 

oseConfAV.ClamActivationOption = new Array(new Array('socket', 'Use Socket'), new Array('tcpip', 'Use TCP IP'));
oseConfAV.ClamActivation = oseGetCombo('clamav_activation', CLAMAV_ACTIVATION_METHOD, oseConfAV.ClamActivationOption, 600, 320, 100, 'socket');

oseConfAV.Form = new Ext.FormPanel({
	    ref: 'form',
	    labelAlign: 'top',
	    frame:false,
	    bodyStyle:'padding:10px',
	    autoScroll: true,
	    width: '100%',
	    height: 550,
	    renderTo: 'ConfAntiVirus',
        items: [
            oseGetDisplayField(O_SCANNED_FILE_EXTENSIONS_DESC),
			oseGetNormalTextArea('file_ext', O_SCANNED_FILE_EXTENSIONS, 320, 600),
			{
                xtype:'textfield',
            	labelWidth: 320,
				fieldLabel: O_DO_NOT_SCAN_BIGGER_THAN,
                name: 'maxfilesize',
                id: 'maxfilesize',
                anchor:'40%',
                regex: /[0-9]/i,
                value: 2
            },
            oseGetDisplayField(O_CLAMAV_DESC),
            oseGetCombo('enable_clamav', O_ENABLE_CLAMAV_SCANNING, oseConfAV.Option, 600, 320, 100, 0),
            oseConfAV.ClamActivation,
	   		{
                xtype:'textfield',
                labelWidth: 320,
                fieldLabel: CLAMAV_SOCKET_LOCATION,
                name: 'clamavsocket',
                id: 'clamavsocket',
                anchor:'70%',
                value: "unix:///tmp/clamd.socket"    
            },{
                xtype:'textfield',
                labelWidth: 320,
                fieldLabel: 'ClamAV TCP IP Address',
                name: 'clamavtcpip',
                id: 'clamavtcpip',
                anchor:'70%',
                value: "127.0.0.1"    
            }
            ,{
                xtype:'textfield',
                labelWidth: 320,
                fieldLabel: 'ClamAV TCP IP Port',
                name: 'clamavtcpport',
                id: 'clamavtcpport',
                anchor:'70%',
                value: "3310"    
            }
		],
		buttons: [{
            text: 'Save',
            handler: function (){
            	oseConfFormSubmit(oseConfAV.Form, url, option, controller, 'saveConfAV', 'vsscan', O_PLEASE_WAIT);
            }
        }],
	  	listeners: oseGetConfListener(url, option, controller, 'getConfiguration', 'vsscan')
    });
