$(document).ready(function() {

    //验证新建活动表单
    $("#frmNew").validationEngine({
        promptPosition: "centerRight"   //topLeft, topRight, bottomLeft, centerRight, bottomRight
    });

    //loadDatePicker
    $.datepicker.regional['zh-CN'] = {  
		clearText: '清除',  
		clearStatus: '清除已选日期',  
		closeText: '关闭',  
		closeStatus: '不改变当前选择',  
		prevText: '上月',  
		prevStatus: '显示上月',  
		prevBigText: '<<',  
		prevBigStatus: '显示上一年',  
		nextText: '下月>',  
		nextStatus: '显示下月',  
		nextBigText: '>>',  
		nextBigStatus: '显示下一年',  
		currentText: '今天',  
		currentStatus: '显示本月',  
		monthNames: ['一月','二月','三月','四月','五月','六月', '七月','八月','九月','十月','十一月','十二月'],  
		monthStatus: '选择月份',  
		yearStatus: '选择年份',  
		weekHeader: '周',  
		weekStatus: '年内周次',  
		dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],  
		dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],  
		dayNamesMin: ['日','一','二','三','四','五','六'],  
		dayStatus: '设置 DD 为一周起始',  
		dateStatus: '选择 m月 d日, DD',  
		initStatus: '请选择日期', 
		dateFormat: 'yy-mm-dd',
		isRTL: false  
	};  
	$.datepicker.setDefaults($.datepicker.regional['zh-CN']);  
	$('.datepicker').datepicker();
	 $("#ui-datepicker-div").css('font-size','12px') //改变大小

    //Load Timepicker
	$.timepicker.regional['zh-CN'] = {
		timeOnlyTitle: '选择时间',
		timeText: '时间',
		hourText: '小时',
		minuteText: '分钟',
		secondText: '秒钟',
		millisecText: '微秒',
		timezoneText: '时区',
		currentText: '当前时间',
		closeText: '完成',
		timeFormat: 'HH:mm',
		amNames: ['AM', 'A'],
		pmNames: ['PM', 'P'],
		isRTL: false
	};
	$.timepicker.setDefaults($.timepicker.regional['zh-CN']);
	$('#time-begin').timepicker({
		hour: 8,
		minute:30,
		hourGrid: 4,
		minuteGrid: 30
	});
	$('#time-end').timepicker({
		hour: 17,
		minute:30,
		hourGrid: 4,
		minuteGrid: 30				
	});

	//Load Tagsit
    var sampleTags = ['望江', '江安', '成都', '篮球赛', '足球赛', '电影','2013','讲座','比赛','竞赛','部门','NBA','宅不起','Zhaibuqi','体育部','文艺部'];
    //this is an INPUT element, rather than a UL as in the other 
    $('#tags').tagit({
        availableTags: sampleTags
        //标签是否允许空格, allowSpaces: true
        //删除标签确认, removeConfirmation: true
    });
}); 