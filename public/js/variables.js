var controller = "variables";

Ext.ns('oseATH','oseATHVARIABLES');

function changeItemStatus(id, status)
{
	oseChangeItemStatus(url, option, controller, 'changeVarStatus', id, status , oseATHVARIABLES.store);
}
oseATHVARIABLES.vartypeOptions = new Array(
			   new Array('POST', 'POST'), 
			   new Array('GET', 'GET'), 
			   new Array('COOKIE', 'COOKIE')
);
oseATHVARIABLES.statusOption = oseGetVarStatusOptions();
oseATHVARIABLES.fields = new Array('id', 'keyname','status', 'statusexp');
oseATHVARIABLES.store = oseGetStore('varstore', oseATHVARIABLES.fields, url, option, controller, 'getVariables');
oseATHVARIABLES.form = Ext.create('Ext.form.Panel', {
		bodyStyle: 'padding: 10px; padding-left: 20px'
		,autoScroll: true
		,autoWidth: true
	    ,border: false
	    ,labelAlign: 'left'
	    ,labelWidth: 150
	    ,buttons: [{
			text: 'Save'
			,handler: function(){
				oseFormSubmit(oseATHVARIABLES.form, url, option, controller, 'addvariables', oseATHVARIABLES.store, O_PLEASE_WAIT);
			}
		}]
    	,items:[
    		oseGetCombo('requesttype', 'Variable_Type', oseATHVARIABLES.vartypeOptions, 350, 100, 100, 'POST'),
    		oseGetNormalTextField('variablefield', O_VARIABLE_NAME, 100, 350, null),
	   		oseGetCombo('statusfield', 'Status', oseGetVarStatusOptions(), 350, 100, 100, 1)
		]
});


oseATHVARIABLES.panel = new Ext.grid.GridPanel({
		id: 'oseATHVARIABLES',
		name: 'oseATHVARIABLES',
		selType: 'rowmodel',
		columns: [
             {id: 'id', header: O_ID,  hidden:false, dataIndex: 'id', width: '5%', sortable:true}
            ,{id: 'keyname', header: O_VARIABLES,  hidden:false, dataIndex: 'keyname', width: '75%', sortable:true}
           	,{id: 'status', header: O_STATUS,  hidden:false, dataIndex: 'status',width: '5%', sortable:true}
           	,{id: 'statusexp', header: O_STATUS_EXP,  hidden:false, dataIndex: 'statusexp',width: '14%', sortable:true}
	    ],
	    sortInfo:{field: 'id', direction: "ASC"},
	    store: oseATHVARIABLES.store,
	    renderTo: 'oseATHVARIABLESPanel',
	    height: 735,
	    tbar: new Ext.Toolbar({
			items: [
					oseGetAddWinButton('addSigbutton', ADD_A_VARIABLE, ADD_A_VARIABLE, oseATHVARIABLES.form, 600),
					{
				        	id: 'delSelected',
				            text: O_DELETE_ITEMS,
				            handler: function(){
				            	osePanelButtonAction(O_DELETE_CONFIRM, 
				            						 O_DELETE_CONFIRM_DESC, 
				             						 oseATHVARIABLES.panel, oseATHVARIABLES, url, option, controller, 
				             						 'deletevariable'
				             	);
				             	
				            }
				    },
				    {
				        	id: 'loadwordpress',
				            text: LOAD_WORDPRESS_DATA,
				            handler: function(){
				            	osePanelButtonAction(O_LOAD_DATA_CONFIRMATION, 
				            						 O_LOAD_DATA_CONFIRMATION_DESC, 
				             						 oseATHVARIABLES.panel, oseATHVARIABLES, url, option, controller, 
				             						 'loadWordpressrules'
				             	);
				            }
				     },
				     {
				        	id: 'cleardata',
				            text: O_CLEAR_DATA,
				            handler: function(){
				            	osePanelButtonAction(O_CLEAR_DATA_CONFIRMATION, 
				            						 O_CLEAR_DATA_CONFIRMATION_DESC, 
				             						 oseATHVARIABLES.panel, oseATHVARIABLES, url, option, controller, 
				             						 'clearvariables'
				             	);
				           }
				      },
					  '->',
					  oseGetStatusFilter(oseATHVARIABLES),
				      ,'-',
				      oseGetSearchField (oseATHVARIABLES)
				    ]
		}),
		bbar: oseGetPaginator(oseATHVARIABLES)
       });
