var url = ajaxurl; 
var controller = "vsscan";
var option = "com_ose_firewall";

jQuery(document).ready(function($){
	//get object with colros from plugin and store it.
	var objColors = $('body').data('appStart').getColors();
	var colours = {
		white: objColors.white,
		dark: objColors.dark,
		red : objColors.red,
		blue: objColors.blue,
		green : objColors.green,
		yellow: objColors.yellow,
		brown: objColors.brown,
		orange : objColors.orange,
		purple : objColors.purple,
		pink : objColors.pink,
		lime : objColors.lime,
		magenta: objColors.magenta,
		teal: objColors.teal,
		textcolor: '#5a5e63',
		gray: objColors.gray
	}
	//generate random number for charts
	randNum = function(){
		//return Math.floor(Math.random()*101);
		return (Math.floor( Math.random()* (1+40-20) ) ) + 20;
	}
	initPlotChart($, [0,0], true);
	initPlotChart($, [0,0], false);
	initPieChartPage($, 20,100,1500, colours);
	$('#vsscan').on('click', function() { 
		showLoading ();
		scanAntivirus (-2, 'vsscan', [], []);
	});
	$('#vsstop').on('click', function() { 
		showLoading ();
		location.reload(); 
	});
	$('#vscont').on('click', function() { 
		runAllScanAntivirus ('vsscan', cpuData=[], memData=[]) 
	});
});

var initPlotChart = function ($, data, cpu) {
	if (cpu =='')
	{
		cpu = false;
	}	
	//define chart colours
	var chartColours = ['#3fc3a8', '#ed7a53', '#9FC569', '#bbdce3', '#9a3b1b', '#5a8022', '#2c7282'];
	var options = {
			grid: {
				show: true,
			    aboveData: true,
			    color:'#3f3f3f',
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
	            	borderColor: "#fff"
	            }
	        },
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
			    		lines: {fillColor: "#f2f7f9"},
			    		points: {fillColor: "#3fc3a8"}
			    }], options);
	}
	else
	{
		var plot2 = $.plot($("#line-chart-memory"),
			    [{
			    		label: "Membory Usage",
			    		data: data,
			    		lines: {fillColor: "#f2f7f9"},
			    		points: {fillColor: "#3fc3a8"}
			    }], options);
	}
	
}

var initPieChartPage = function($, lineWidth, size, animateTime, colours) {
	$(".easy-pie-chart").easyPieChart({
        barColor: colours.dark,
        borderColor: colours.dark,
        trackColor: colours.gray,
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-red").easyPieChart({
        barColor: colours.red,
        borderColor: colours.red,
        trackColor: '#fbccbf',
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-green").easyPieChart({
        barColor: colours.green,
        borderColor: colours.green,
        trackColor: '#b1f8b1',
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-blue").easyPieChart({
        barColor: colours.blue,
        borderColor: colours.blue,
        trackColor: '#d2e4fb',
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-teal").easyPieChart({
        barColor: colours.teal,
        borderColor: colours.teal,
        trackColor: '#c3e5e5',
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-purple").easyPieChart({
        barColor: colours.purple,
        borderColor: colours.purple,
        trackColor: '#dec1f5',
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-orange").easyPieChart({
        barColor: colours.orange,
        borderColor: colours.orange,
        trackColor: '#f9d7af',
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
    $(".easy-pie-chart-lime").easyPieChart({
        barColor: colours.lime,
        borderColor: colours.lime,
        trackColor: '#cfed93',
        scaleColor: false,
        lineCap: 'butt',
        lineWidth: lineWidth,
        size: size,
        animate: animateTime
    });
}

function scanAntivirus (step, action, cpuData, memData) {
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
	        	initPlotChart($, cpuData, true);
	        	initPlotChart($, memData, false);
	        	$('#p4text').html(data.summary);
	        	$('#last_file').html(data.last_file);
	        	if (step == -2 && data.cont==true)
	        	{
	        		scanAntivirus (-1, action, cpuData, memData);
	        	}
	        	else
	        	{
	        		runAllScanAntivirus (action, cpuData, memData);
	        	}
	        }
	      });
	});
}

function runAllScanAntivirus (action, cpuData, memData) {
	var s1 = scanVirusInd (action, cpuData, memData, 1);
	var s2 = scanVirusInd (action, cpuData, memData, 2);
	var s3 = scanVirusInd (action, cpuData, memData, 3);
	var s4 = scanVirusInd (action, cpuData, memData, 4);
	var s5 = scanVirusInd (action, cpuData, memData, 5);
	var s6 = scanVirusInd (action, cpuData, memData, 6);
	var s7 = scanVirusInd (action, cpuData, memData, 7);
	var s8 = scanVirusInd (action, cpuData, memData, 8);
	jQuery(document).ready(function($){
		$.when(s1, s2, s3, s4, s5, s6, s7, s8).then(
			function ( v1, v2, v3, v4, v5, v6, v7, v8 ) {
		});
	});
}

function scanVirusInd (action, cpuData, memData, type) {
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
		        	initPlotChart($, cpuData, true);
		        	initPlotChart($, memData, false);
	        	}	
	        	$('#p4text').html(data.summary);
	        	$('#last_file').html(data.last_file);
	        	$('#easy-pie-chart-'+type).data('easyPieChart').update(data.completed);
	        	$('#easy-pie-chart-'+type).attr("data-percent",data.completed);
	        	$('#pie-'+type).html(data.completed+'%');
	        	if (data.cont==true)
	        	{
	        		scanVirusInd (action, cpuData, memData, type);
	        	}
	        	else
	        	{
	        		return true;
	        	}
	        },
		    error: function(XMLHttpRequest, textStatus, errorThrown) {
		    	scanVirusInd (action, cpuData, memData, type);
		    }
	      });
	});		
}

