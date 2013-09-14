














var Runner = function(){
    var f = function(v, pbar, btn, count, cb){
        return function(){
            if(v > count){
                btn.dom.disabled = false;
                cb();
            }else{
                if(pbar.id=='pbar4'){
                    //give this one a different count style for fun
                    var i = v/count;
                    pbar.updateProgress(i, Math.round(100*i)+'% completed...');
                }else{
                    pbar.updateProgress(v/count, 'Loading item ' + v + ' of '+count+'...');
                }
            }
       };
    };
    return {
        run : function(pbar, btn, count, cb) {
            btn.dom.disabled = true;
            var ms = 5000/count;
            for(var i = 1; i < (count+2); i++){
               setTimeout(f(i, pbar, btn, count, cb), i*ms);
            }
        }
    };
}();


//==== Progress bar 4 ====
var pbar4 = Ext.create('Ext.ProgressBar', {
    text:'Waiting on you...',
    id:'pbar4',
    textEl:'p4text',
    cls:'custom',
    renderTo:'p4'
});

var init = Ext.get('init');


init.on('click', function() {
    Runner.run(pbar4, init, 19, function() {
        pbar4.updateText('All finished!');
    });
});


function initDB () {
	var win = oseGetWIn('initDB', 'Initialise Database', 1024, 500); 
	win.show(); 
	win.update('Database initialisation in progress');
	initDatabase (0, win);
}

oseATHScanner.form = Ext.create('Ext.form.Panel', {
	bodyStyle: 'padding: 10px; padding-left: 20px'
	,autoScroll: true
	,autoWidth: true
    ,border: false
    ,labelAlign: 'left'
    ,labelWidth: 150
    ,buttons: [
    {
		text: 'Save'
		,handler: function(){
			if (oseCheckIPValidity()==false) { return false; }
			oseFormSubmit(oseATHScanner.form, url, option, controller, 'addips', null, 'Please wait, this will take a few seconds ...');
		}
	}
	]
    ,items:[
		oseGetNormalTextField('title', O_IP_RULE, 100, 350, null)
    ]
});


var wintest = new Ext.Window({
	title: 'test'
	,modal: true
	,width: 800
	,border: false
	,autoHeight: true
	,closeAction:'hide'
	,items: [
	         oseATHScanner.form
	]
});	






function initDatabase (step, win) {
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
