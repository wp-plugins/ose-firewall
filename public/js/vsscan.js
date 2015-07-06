var url = ajaxurl; 
var controller = "vsscan";
var option = "com_ose_firewall";

jQuery(document).ready(function($){
	//get object with colros from plugin and store it.
    colours = $('body').data('appStart').getColors();
    var sizes = $('body').data('appStart').getSizes();
    initPlotChart($, [0, 0], true, colours);
    initPlotChart($, [0, 0], false, colours);
    initPieChartPage($, sizes.pielinewidth, sizes.piesize, 1500, colours);
	$('#vsscan').on('click', function() {
        initPieChartPage($, sizes.pielinewidth, sizes.piesize, 1500, colours);
		showLoading ();
        scanAntivirus(-3, 'vsscan', [], [], colours);
	});
	$('#vsscanSing').on('click', function() {
        initPieChartPage($, sizes.pielinewidth, sizes.piesize, 1500, colours);
		showLoading ();
        scanAntivirusSing(-3, 'vsscan', [], [], colours);
	});
	$('#vsstop').on('click', function() { 
		showLoading ();
		location.reload(); 
	});
	$('#vscont').on('click', function() { 
		scanAntivirus (-2, 'vsscan', [], [], colours);
	});
});
jQuery(document).ready(function ($) {
    $("#scan-form").submit(function () {
        $('#scanModal').modal('hide');
        showLoading();
        var data = $("#scan-form").serialize();
        data += '&centnounce=' + $('#centnounce').val();
        $.ajax({
            type: "POST",
            url: url,
            data: data, // serializes the form's elements.
            success: function (data) {
                hideLoading();
                data = jQuery.parseJSON(data);
                if (data.cont) {
                    scanAntivirus(-2, 'vsscan', [], [], colours);
                }
            }
        });
        return false; // avoid to execute the actual submit of the form.
    });
})
var initPlotChart = function ($, data, cpu, colours) {
	if (cpu =='')
	{
		cpu = false;
	}
	//define chart colours
    var chartColours = [colours.linechart1, colours.linechart2, colours.linechart3, colours.linechart4, colours.linechart5, colours.linechart6, colours.linechart7];
	var options = {
			grid: {
				show: true,
			    aboveData: true,
                color: colours.black,
			    labelMargin: 15,
                axisMargin: 0,
			    borderWidth: 0,
			    borderColor:null,
			    minBorderMargin: 0,
			    clickable: true, 
			    hoverable: true,
			    autoHighlight: true,
			    mouseActiveRadius: 20
			},
	        series: {
	        	grow: {active:false},
	            lines: {
            		show: true,
            		fill: true,
            		lineWidth: 2,
            		steps: false
	            	},
	            points: {
	            	show:true,
	            	radius: 4,
	            	symbol: "circle",
	            	fill: true,
                    borderColor: colours.white
	            }
	        },
        yaxis: {min: 0},
        xaxis: {min: 0},
	        legend: { position: "se" },
	        colors: chartColours,
	        shadowSize:1,
	        tooltip: true, //activate tooltip
			tooltipOpts: {
				content: "%s : %y.0",
				shifts: {
					x: -30,
					y: -50
				}
			}
	};  
	if (cpu == true)
	{
		var plot1 = $.plot($("#line-chart-cpu"),
			    [{
			    		label: "CPU Load",
			    		data: data,
                    lines: {fillColor: colours.cream},
                    points: {fillColor: colours.linechart1}
			    }], options);
	}
	else
	{
		var plot2 = $.plot($("#line-chart-memory"),
			    [{
			    		label: "Memory Usage",
			    		data: data,
                    lines: {fillColor: colours.cream},
                    points: {fillColor: colours.linechart1}
			    }], options);
	}
	
}

var initPieChartPage = function($, lineWidth, size, animateTime, colours) {
	$(".easy-pie-chart").easyPieChart({
        barColor: colours.dark,
        borderColor: colours.dark,
        trackColor: colours.piedark,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-red").easyPieChart({
        barColor: colours.red,
        borderColor: colours.red,
        trackColor: colours.piered,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-green").easyPieChart({
        barColor: colours.green,
        borderColor: colours.green,
        trackColor: colours.piegreen,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-blue").easyPieChart({
        barColor: colours.blue,
        borderColor: colours.blue,
        trackColor: colours.pieblue,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-teal").easyPieChart({
        barColor: colours.teal,
        borderColor: colours.teal,
        trackColor: colours.pieteal,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-purple").easyPieChart({
        barColor: colours.purple,
        borderColor: colours.purple,
        trackColor: colours.piepurple,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-orange").easyPieChart({
        barColor: colours.orange,
        borderColor: colours.orange,
        trackColor: colours.pieorange,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-lime").easyPieChart({
        barColor: colours.lime,
        borderColor: colours.lime,
        trackColor: colours.pielime,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
}

function scanAntivirus(step, action, cpuData, memData, colours) {
	jQuery(document).ready(function($){
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:action,
		    		task:action,
		    		step : step,
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	        	hideLoading ();
	        	cpuData.push([cpuData.length, data.cpuload]);
	        	memData.push([memData.length, data.memory]);
                initPlotChart($, cpuData, true, colours);
                initPlotChart($, memData, false, colours);
	        	$('#p4text').html(data.summary);
	        	$('#last_file').html(data.last_file);
	        	if ((step == -3 && data.contFileScan==true) || (step == -2 && data.contFileScan==true))
	        	{
                    scanAntivirus(-2, action, cpuData, memData, colours);
	        	}
	        	else if (step == -2 && data.cont==true) {
                    scanAntivirus(-1, action, cpuData, memData, colours);
	        	}
	        	else
	        	{
                    runAllScanAntivirus(action, cpuData, memData, colours);
	        	}
	        }
	      });
	});
}

function runAllScanAntivirus(action, cpuData, memData, colours) {
    var s1 = scanVirusInd(action, cpuData, memData, 1, colours);
    var s2 = scanVirusInd(action, cpuData, memData, 2, colours);
    var s3 = scanVirusInd(action, cpuData, memData, 3, colours);
    var s4 = scanVirusInd(action, cpuData, memData, 4, colours);
    var s5 = scanVirusInd(action, cpuData, memData, 5, colours);
    var s6 = scanVirusInd(action, cpuData, memData, 6, colours);
    var s7 = scanVirusInd(action, cpuData, memData, 7, colours);
    var s8 = scanVirusInd(action, cpuData, memData, 8, colours);
	jQuery(document).ready(function($){
		$.when(s1, s2, s3, s4, s5, s6, s7, s8).then(
			function ( v1, v2, v3, v4, v5, v6, v7, v8 ) {
		});
	});
}

function scanVirusInd(action, cpuData, memData, type, colours) {
	jQuery(document).ready(function($){
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:action,
		    		task:action,
		    		step:0,
		    		type:type,
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	        	hideLoading ();
	        	if (type==2)
	        	{
	        		cpuData.push([cpuData.length, data.cpuload]);
		        	memData.push([memData.length, data.memory]);
                    initPlotChart($, cpuData, true, colours);
                    initPlotChart($, memData, false, colours);
	        	}	
	        	$('#p4text').html(data.summary);
	        	$('#last_file').html(data.last_file);
	        	$('#easy-pie-chart-'+type).data('easyPieChart').update(data.completed);
	        	$('#easy-pie-chart-'+type).attr("data-percent",data.completed);
	        	$('#pie-'+type).html(data.completed+'%');
	        	if (data.cont==true)
	        	{
                    scanVirusInd(action, cpuData, memData, type, colours);

                }
	        	else
	        	{
	        		return true;
	        	}
	        },
		    error: function(XMLHttpRequest, textStatus, errorThrown) {
                scanVirusInd(action, cpuData, memData, type, colours);
		    }
	      });
	});		
}

function scanAntivirusSing(step, action, cpuData, memData, colours) {
	jQuery(document).ready(function($){
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:action,
		    		task:action,
		    		step : step,
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	        	hideLoading ();
	        	cpuData.push([cpuData.length, data.cpuload]);
	        	memData.push([memData.length, data.memory]);
                initPlotChart($, cpuData, true, colours);
                initPlotChart($, memData, false, colours);
	        	$('#p4text').html(data.summary);
	        	$('#last_file').html(data.last_file);
	        	if ((step == -2 && data.contFileScan==true)|| (step == -3 && data.contFileScan==true))
	        	{
                    scanAntivirusSing(-2, action, cpuData, memData, colours);
	        	}
	        	else if (step == -2 && data.cont==true) {
                    scanAntivirusSing(-1, action, cpuData, memData, colours);
	        	}
	        	else
	        	{
                    scanVirusSingInd(action, cpuData, memData, 1, colours);
	        	}
	        }
	      });
	});
}

function scanVirusSingInd(action, cpuData, memData, type, colours) {
	jQuery(document).ready(function($){
		$.ajax({
	        type: "POST",
	        url: url,
	        dataType: 'json',
		    data: {
		    		option : option, 
		    		controller:controller,
		    		action:action,
		    		task:action,
		    		step:0,
		    		type:type,
		    		centnounce:$('#centnounce').val()
		    },
	        success: function(data)
	        {
	        	hideLoading ();
	        	if (type==2)
	        	{
	        		cpuData.push([cpuData.length, data.cpuload]);
		        	memData.push([memData.length, data.memory]);
                    initPlotChart($, cpuData, true, colours);
                    initPlotChart($, memData, false, colours);
	        	}	
	        	$('#p4text').html(data.summary);
	        	$('#last_file').html(data.last_file);
	        	$('#easy-pie-chart-'+type).data('easyPieChart').update(data.completed);
	        	$('#easy-pie-chart-'+type).attr("data-percent",data.completed);
	        	$('#pie-'+type).html(data.completed+'%');
	        	if (data.cont==true)
	        	{
                    scanVirusSingInd(action, cpuData, memData, type, colours);
	        	}
	        	else
	        	{
	        		if (type<8) {
	        			type = type +1;
                        scanVirusSingInd(action, cpuData, memData, type, colours);
	        		}
	        		else
	        		{
	        			showLoading ('Scanning completed');
	        			hideLoading ();
	        		}
	        	}
	        },
		    error: function(XMLHttpRequest, textStatus, errorThrown) {
                scanVirusSingInd(action, cpuData, memData, type, colours);
		    }
	      });
	});		
}
function downloadRequest(type) {
    showLoading();
    jQuery(document).ready(function ($) {
        showLoading();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: "advancerulesets",
                action: 'downloadRequest',
                task: 'downloadRequest',
                type: type,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                hideLoading();
                downloadSQL(type, data.downloadKey, data.version);
            }
        });
    });
}
function downloadSQL(type, downloadKey, version) {
    jQuery(document).ready(function ($) {
        showLoading();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {
                option: option,
                controller: "advancerulesets",
                action: 'downloadSQL',
                task: 'downloadSQL',
                type: type,
                downloadKey: downloadKey,
                version: version,
                centnounce: $('#centnounce').val()
            },
            success: function (data) {
                showLoading(data.result);
                hideLoading();
                if (data.status == "ERROR"){
                    showDialogue(O_VSPATTERN_UPDATE_FAIL, O_FAIL, O_OK);
                } else {
                    showDialogue(O_VSPATTERN_UPDATE, O_SUCCESS, O_OK);
                }
                }
        });
    });
}

jQuery(document).ready(function($){
    $( '#FileTreeDisplay' ).html( '<ul class="filetree start"><li class="wait">' + 'Generating Tree...' + '<li></ul>' );
    getfilelist( $('#FileTreeDisplay') , '' );
    $( '#FileTreeDisplay' ).on('click', 'LI', function() { /* monitor the click event on foldericon */
        var entry = $(this);
        var current = $(this);
        var id = 'id';
        getfiletreedisplay (entry, current, id);
        return false;
    });
    $( '#FileTreeDisplay' ).on('click', 'LI A', function() { /* monitor the click event on links */
        var currentfolder;
        var current = $(this);
        currentfolder = current.attr('id')
        $("#selected_file").val(currentfolder) ;
        return false;
    });
});