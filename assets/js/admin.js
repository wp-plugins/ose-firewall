if(! window['osefirewallAdmin']){
window['osefirewallAdmin'] = {
	loading16: '<div class="osefwLoading16"></div>',
	actUpdateInterval: 2000,
	dbCheckTables: [],
	dbCheckCount_ok: 0,
	dbCheckCount_skipped: 0,
	dbCheckCount_errors: 0,
	issues: [],
	ignoreData: false,
	iconErrorMsgs: [],
	scanIDLoaded: 0,
	colorboxQueue: [],
	colorboxOpen: false,
	mode: '',
	visibleIssuesPanel: 'new',
	preFirstScanMsgsLoaded: false,
	newestActivityTime: 0, //must be 0 to force loading of all initially
	elementGeneratorIter: 1,
	reloadConfigPage: false,
	nonce: false,
	request: '',
	tickerUpdatePending: false,
	activityLogUpdatePending: false,
	lastALogCtime: 0,
	activityQueue: [],
	totalActAdded: 0,
	maxActivityLogItems: 1000,
	scanReqAnimation: false,
	debugOn: true,
	blockedCountriesPending: [],
	ownCountry: "",
	schedStartHour: false,
	currentPointer: false,
	countryMap: false,
	countryCodesToSave: "",
	init: function(){
		this.nonce = osefirewallAdminVars.firstNonce; 
		this.debugOn = osefirewallAdminVars.debugOn == '1' ? true : false;
		this.tourClosed = osefirewallAdminVars.tourClosed == '1' ? true : false;
		if(jQuery('#osefirewallMode_scan').length > 0){
			this.mode = 'scan';
			jQuery('#consoleActivity').scrollTop(jQuery('#consoleActivity').prop('scrollHeight'));
			jQuery('#consoleScan').scrollTop(jQuery('#consoleScan').prop('scrollHeight'));
		} 
		else {
			this.mode = false;
		}
		this.showTotalFiles();
		this.showInfectedFiles();
	},
	showLoading: function(){
		this.removeLoading();
		jQuery('<div id="wordfenceWorking">OSE Fireall is working...</div>').appendTo('body');
	},
	removeLoading: function(){
		jQuery('#wordfenceWorking').remove();
	},
	colorbox: function(width, heading, body){ 
		this.colorboxQueue.push([width, heading, body]);
		this.colorboxServiceQueue();
	},
	showTotalFiles: function (){
		this.ajax('osefirewall_showtotal', {}, function(res){ 
			jQuery('#consoleSummary').append('<div>'+res.summary+'</div>');
		});
	},
	showInfectedFiles: function (){
		this.ajax('osefirewall_showinfected', {}, function(res){ 
			jQuery('#consoleSummary').append('<div>'+res.summary+'</div>');
		});
	},
	startScan: function(init){
		jQuery('#wfStartScanButton1').prop('value', "Locked - Initializing");
		jQuery('#wfStartScanButton1').attr('disabled', 'disabled');
		data = {};
		data.init=init;
		if (init==true)
		{
			jQuery('#ui-pbar').addClass('ui-progress');
			jQuery('#consoleSummary').html('Start initialising data');
			jQuery('#consoleActivity').html('');
		}
		
		this.ajax('osefirewall_scan', data, function(res){ 
			jQuery('#consoleActivity').append('<div>' + res.summary +'</div>');
			jQuery('#consoleActivity').scrollTop(jQuery('#consoleActivity').prop('scrollHeight'));
			
			if (res.cont==true)
			{
				osefirewallAdmin.startScan(0);
			}	
			else
			{
				jQuery('#consoleSummary').append('<div>'+res.summary+'</div>');
				jQuery('#wfStartScanButton1').prop('value', "Initialization Completed");
				jQuery('#ui-pbar').removeClass('ui-progress');
				jQuery('#wfStartScanButton1').removeAttr('disabled');
				return false;
			}
		} );
	},
	startvsScan: function(init){
		jQuery('#wfStartScanButton2').prop('value', "Locked - Scanning");
		jQuery('#wfStartScanButton2').attr('disabled', 'disabled');
		data = {};
		data.init=init;
		if (init==true)
		{
			jQuery('#ui-pbar').addClass('ui-progress');
			jQuery('#consoleSummary').html('Start initialising data');
			jQuery('#consoleActivity').html('');
		}
		
		this.ajax('osefirewall_scanvs', data, function(res){ 
			if (res.found)
			{
				jQuery('#consoleSummary').append('<div>'+res.found+'</div>');
			}	
			jQuery('#consoleActivity').append('<div>' + res.summary +'</div>');
			jQuery('#consoleActivity').scrollTop(jQuery('#consoleActivity').prop('scrollHeight'));
			
			if (res.cont==true)
			{
				osefirewallAdmin.startvsScan(0);
			}	
			else
			{
				jQuery('#wfStartScanButton2').prop('value', "Scanning Completed");
				osefirewallAdmin.showInfectedFiles();
				jQuery('#ui-pbar').removeClass('ui-progress');
				jQuery('#wfStartScanButton2').removeAttr('disabled');
				return false;
			}
		} );
	}, 
	convsscan: function ()
	{
		osefirewallAdmin.startvsScan(0);
	},
	ajax: function(action, data, cb, cbErr, noLoading){
		if(typeof(data) == 'string'){
			if(data.length > 0){
				data += '&';
			}
			data += 'action=' + action + '&nonce=' + this.nonce;
		} else if(typeof(data) == 'object'){
			data['action'] = action;
			data['nonce'] = this.nonce;
		}
		if(! cbErr){
			cbErr = function(){};
		}
		var self = this;
		if(! noLoading){
			this.showLoading();
		}
		this.request = jQuery.ajax({
			type: 'POST',
			url: osefirewallAdminVars.ajaxURL,
			dataType: "json",
			data: data,
			success: function(json){ 
				self.removeLoading();
				if(json && json.nonce){
					self.nonce = json.nonce;
				}
				if(json && json.errorMsg){
					self.colorbox('400px', 'An error occurred', json.errorMsg);
				}
				cb(json); 
			},
			error: function(){ self.removeLoading(); cbErr(); }
			});
	},
	stopAjax: function ()
	{
		this.request.abort();
		jQuery('#consoleSummary').append('<div>User aborted</div>');
		jQuery('#wfStartScanButton1').prop('value', "Start Initialization");
		jQuery('#ui-pbar').removeClass('ui-progress');
		jQuery('#wfStartScanButton1').removeAttr('disabled');
		
		jQuery('#wfStartScanButton2').prop('value', "Start Scanning");
		jQuery('#wfStartScanButton2').removeAttr('disabled');
		
	},
	colorboxServiceQueue: function(){
		if(this.colorboxIsOpen){ return; }
		if(this.colorboxQueue.length < 1){ return; }
		var elem = this.colorboxQueue.shift();
		this.colorboxOpen(elem[0], elem[1], elem[2]);
	},
	colorboxOpen: function(width, heading, body){
		this.colorboxIsOpen = true;
		jQuery.colorbox({ width: width, html: "<h3>" + heading + "</h3><p>" + body + "</p>"});
	},
	scanRunningMsg: function(){ this.colorbox('400px', "A scan is running", "A scan is currently in progress. Please wait until it finishes before starting another scan."); },
	errorMsg: function(msg){ this.colorbox('400px', "An error occurred:", msg); },
	confirmUpdateAllIssues: function(op){
		var self = this;
		this.ajax('wordfence_updateAllIssues', { op: op }, function(res){ self.loadIssues(); });
	},
	es: function(val){
		if(val){
			return val;
		} else {
			return "";
		}
	},
	noQuotes: function(str){
		return str.replace(/"/g,'&#34;').replace(/\'/g, '&#145;');
	},
	commify: function(num){
		return ("" + num).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
	},
	ucfirst: function(str){
		str = "" + str;
		return str.charAt(0).toUpperCase() + str.slice(1);
	},
	makeTimeAgo: function(t){
		var months = Math.floor(t / (86400 * 30));
		var days = Math.floor(t / 86400);
		var hours = Math.floor(t / 3600);
		var minutes = Math.floor(t / 60);
		if(months > 0){
			days -= months * 30;
			return this.pluralize(months, 'month', days, 'day');
		} else if(days > 0){
			hours -= days * 24;
			return this.pluralize(days, 'day', hours, 'hour');
		} else if(hours > 0) {
			minutes -= hours * 60;
			return this.pluralize(hours, 'hour', minutes, 'min');
		} else if(minutes > 0) {
			//t -= minutes * 60;
			return this.pluralize(minutes, 'minute');
		} else {
			return Math.round(t) + " seconds";
		}
	},
	pluralize: function(m1, t1, m2, t2){
		if(m1 != 1) {
			t1 = t1 + 's';
		}
		if(m2 != 1) {
			t2 = t2 + 's';
		}
		if(m1 && m2){
			return m1 + ' ' + t1 + ' ' + m2 + ' ' + t2;
		} else {
			return m1 + ' ' + t1;
		}
	},
	pulse: function(sel){
		jQuery(sel).fadeIn(function(){
			setTimeout(function(){ jQuery(sel).fadeOut(); }, 2000);
			});
	}
};
window['FWAD'] = window['osefirewallAdmin'];
}
jQuery(function(){
	osefirewallAdmin.init();
});
