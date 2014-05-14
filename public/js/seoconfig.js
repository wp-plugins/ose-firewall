var controller = "seoconfig";

Ext.ns('oseATH', 'oseConfSEO');
oseConfSEO.Option = oseGetYesORNoOptions();
oseConfSEO.Form = Ext.create('Ext.form.Panel', {
	ref : 'form',
	labelAlign : 'top',
	frame : false,
	renderTo : 'ConfigSEO',
	bodyStyle : 'padding:10px',
	autoScroll : false,
	labelWidth : 250,
	height: 780,
	items : [   oseGetNormalTextField('pageTitle', O_SEO_PAGE_TITLE, 200, 1024), 
	            oseGetNormalTextField('metaKeywords', O_SEO_META_KEY, 200, 1024),
				oseGetNormalTextField('metaDescription', O_SEO_META_DESC, 200, 1024), 
				oseGetNormalTextField('metaGenerator', O_SEO_META_GENERATOR, 200, 1024),
				oseGetNormalTextField('adminEmail', O_WEBMASTER_EMAIL, 200, 1024), 
				oseGetTinyMCEEditor('customBanpage', O_CUSTOM_BAN_PAGE, 200, 1024, 450),
				oseGetCombo('scanGoogleBots', O_SCAN_GOOGLE_BOTS, oseConfSEO.Option, 350, 200, 100, 0),
				oseGetCombo('scanMsnBots', O_SCAN_YAHOO_BOTS, oseConfSEO.Option, 350, 200, 100, 0),
				oseGetCombo('scanYahooBots', O_SCAN_MSN_BOTS, oseConfSEO.Option, 350, 200, 100, 0) ],
	buttons : [ {
		text : 'Save',
		handler : function() {
			var form = oseConfSEO.Form.getForm();	
			var adminEmail = form.findField("adminEmail").getValue();
			if (adminEmail === "") {
				Ext.Msg.alert('ERROR', O_PLS_ENTER_ADMIN_EMAIL);
				return false;
			}
			oseConfFormSubmit(oseConfSEO.Form, url, option, controller, 'saveConfigSEO', 'seo', O_PLEASE_WAIT);
		}
	}, 
	],
	listeners : oseGetConfListener(url, option, controller, 'getConfiguration', 'seo')
});
