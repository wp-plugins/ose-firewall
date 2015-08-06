var controller = "emailconfig";

Ext.ns('oseEmails');
oseEmails.fields = ['id', 'subject'];
oseEmails.store = oseGetStore('emailstore', oseEmails.fields, url, option, controller, 'getEmails');
oseEmails.options = oseGetFirewallAlertOptions;

oseEmails.tbar = new Ext.Toolbar({
	items : [ '->', {
		ref : 'editBtn',
		id : 'editBtn',
		name : 'editBtn',
		text : O_EDIT,
		disabled : true,
		handler : function() {
			var sel = oseEmails.grid.getSelectionModel();
			var selections = sel.selected.items;
			oseEmails.form = oseGetEmailEditForm(url, option, controller, 'saveemail', oseEmails.store, oseEmails.options, 'getEmail', selections[0].data.id);
			oseEmails.emailParams = oseGetEmailParamsPanel(url, controller, selections[0].data.id, oseEmails.store);
			oseEmails.editWin = new Ext.Window({
				title : O_EDIT_EMAIL_TEMP,
				modal : true,
				border : false,
				autoHeight : true,
				width : '100%',
				closeAction : 'destroy',
				items : [ 
				    Ext.create('Ext.panel.Panel', {
						bodyPadding : 5,
						layout : {
							type : 'table',
							columns : 2
						},
						items : [ oseEmails.form, oseEmails.emailParams ]
					}) 
				]
			});
			oseEmails.editWin.show().alignTo(Ext.getBody(), 't-t');
		}
	} ]
});

oseEmails.grid = Ext.create('Ext.grid.Panel', {
	id : 'oseEmailsPanel',
	name : 'oseEmailsPanel',
	store : oseEmails.store,
	selType : 'rowmodel',
	renderTo : 'oseEmailsList',
	columns : [ {
		id : 'id',
		header : 'ID',
		dataIndex : 'id',
		width : '5%',
		hidden : false
	}, {
		id : 'subject',
		header : 'Subject',
		dataIndex : 'subject',
		width : '94%',
		sortable : true
	} ],
	sortInfo : {
		field : 'id',
		direction : "DESC"
	},
	height : 500,
	width : '100%',
	tbar : oseEmails.tbar,
	bbar : oseGetPaginator(oseEmails)
});

oseEmails.grid.getSelectionModel().on('selectionchange', function(sm) {
	Ext.getCmp('editBtn').setDisabled(sm.getCount() != 1);
});
