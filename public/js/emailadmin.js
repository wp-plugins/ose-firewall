var controller = "emailadmin";

Ext.ns('oseAdminEmail');
function changeItemStatus(id, status) {
	oseChangeItemStatus(url, option, controller, 'changeRuleStatus', id, status, oseAdminEmail.store);
}
oseAdminEmail.statusOption = oseGetRuleStatusOptions();
oseAdminEmail.fields = new Array('id', 'subject', 'name', 'user_id', 'email_id');
oseAdminEmail.store = oseGetStore('emailstore', oseAdminEmail.fields, url, option, controller, 'getAdminEmailmap');
oseAdminEmail.options = oseGetFirewallAlertOptions;

oseAdminEmail.userfields = new Array('id', 'name');
oseAdminEmail.userlist = oseGetStore('userStore', oseAdminEmail.userfields, url, option, controller, 'getAdminUsers');

oseAdminEmail.emailfields = new Array('id', 'subject');
oseAdminEmail.emaillist = oseGetStore('emailStore', oseAdminEmail.emailfields, url, option, controller, 'getEmailList');

oseAdminEmail.form = Ext.create('Ext.form.Panel', {
	bodyStyle : 'padding: 10px; padding-left: 20px',
	autoScroll : true,
	autoWidth : true,
	border : false,
	labelAlign : 'left',
	labelWidth : 150,
	buttons : [ {
		text : 'Save',
		handler : function() {
			oseFormSubmit(oseAdminEmail.form, url, option, controller, 'addadminemailmap', oseAdminEmail.store, O_PLEASE_WAIT);
		}
	} ],
	items : [ oseGetAjaxCombo('useridfield', O_USER, oseAdminEmail.userlist, 450, 100, 100, 'id', 'name'),
			oseGetAjaxCombo('emailidfield', O_EMAIL, oseAdminEmail.emaillist, 450, 100, 100, 'id', 'subject') ]
});

oseAdminEmail.panel = new Ext.grid.GridPanel({
	id : 'oseAdminEmail',
	name : 'oseAdminEmail',
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
	store : oseAdminEmail.store,
	renderTo : 'oseAdminEmail',
	height : 735,
	tbar : new Ext.Toolbar({
		items : [
				oseGetAddWinButton('addLinkbutton', O_ADD_A_LINK, O_ADD_A_LINK, oseAdminEmail.form, 600),
				{
					id : 'delSelected',
					text : O_DELETE_ITEMS,
					handler : function() {
						osePanelAdminEmailAction(O_DELETE_CONFIRM, O_DELETE_CONFIRM_DESC, oseAdminEmail.panel, oseAdminEmail, url, option, controller,
								'deleteadminemailmap');
					}
				}, '->', oseGetSearchField(oseAdminEmail) ]
	}),
	bbar : oseGetPaginator(oseAdminEmail)
});
