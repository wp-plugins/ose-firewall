var controller = "emailadmin";

Ext.ns('OSEADMINEMAIL');
function changeItemStatus(id, status) {
	oseChangeItemStatus(url, option, controller, 'changeRuleStatus', id, status, OSEADMINEMAIL.store);
}
OSEADMINEMAIL.statusOption = oseGetRuleStatusOptions();
OSEADMINEMAIL.fields = new Array('id', 'subject', 'name', 'user_id', 'email_id');
OSEADMINEMAIL.store = oseGetStore('emailstore', OSEADMINEMAIL.fields, url, option, controller, 'getAdminEmailmap');
OSEADMINEMAIL.options = oseGetFirewallAlertOptions;

OSEADMINEMAIL.userfields = new Array('id', 'name');
OSEADMINEMAIL.userlist = oseGetStore('userStore', OSEADMINEMAIL.userfields, url, option, controller, 'getAdminUsers');

OSEADMINEMAIL.emailfields = new Array('id', 'subject');
OSEADMINEMAIL.emaillist = oseGetStore('emailStore', OSEADMINEMAIL.emailfields, url, option, controller, 'getEmailList');

OSEADMINEMAIL.form = Ext.create('Ext.form.Panel', {
	bodyStyle : 'padding: 10px; padding-left: 20px',
	autoScroll : true,
	autoWidth : true,
	border : false,
	labelAlign : 'left',
	labelWidth : 150,
	buttons : [ {
		text : 'Save',
		handler : function() {
			oseFormSubmit(OSEADMINEMAIL.form, url, option, controller, 'addadminemailmap', OSEADMINEMAIL.store, O_PLEASE_WAIT);
		}
	} ],
	items : [ oseGetAjaxCombo('useridfield', O_USER, OSEADMINEMAIL.userlist, 450, 100, 100, 'id', 'name'),
			oseGetAjaxCombo('emailidfield', O_EMAIL, OSEADMINEMAIL.emaillist, 450, 100, 100, 'id', 'subject') ]
});

OSEADMINEMAIL.panel = new Ext.grid.GridPanel({
	id : 'OSEADMINEMAIL',
	name : 'OSEADMINEMAIL',
	selType : 'rowmodel',
	columns : [ {
		id : 'id',
		header : O_ID,
		hidden : false,
		dataIndex : 'id',
		width : 40,
		sortable : true
	}, {
		id : 'user_id',
		header : O_USER_ID,
		hidden : false,
		dataIndex : 'user_id',
		width : '10%',
		sortable : true
	}, {
		id : 'name',
		header : O_USER,
		hidden : false,
		dataIndex : 'name',
		width : '20%',
		sortable : true
	}, {
		id : 'subject',
		header : O_EMAIL,
		hidden : false,
		dataIndex : 'subject',
		width : '65%',
		sortable : true
	} ],
	sortInfo : {
		field : 'id',
		direction : "ASC"
	},
	store : OSEADMINEMAIL.store,
	renderTo : 'oseAdminEmail',
	height : 500,
	tbar : new Ext.Toolbar({
		items : [
				oseGetAddWinButton('addLinkbutton', O_ADD_A_LINK, O_ADD_A_LINK, OSEADMINEMAIL.form, 600),
				{
					id : 'delSelected',
					text : O_DELETE_ITEMS,
					handler : function() {
						osePanelAdminEmailAction(O_DELETE_CONFIRM, O_DELETE_CONFIRM_DESC, OSEADMINEMAIL.panel, OSEADMINEMAIL, url, option, controller,
								'deleteadminemailmap');
					}
				}, '->', oseGetSearchField(OSEADMINEMAIL) ]
	}),
	bbar : oseGetPaginator(OSEADMINEMAIL)
});
