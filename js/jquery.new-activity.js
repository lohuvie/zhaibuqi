$(document).ready(function() {

    //验证新建活动表单，载入ValidationEngine
    $("#frmNew").validationEngine({
        promptPosition: "centerRight"   //topLeft, topRight, bottomLeft, centerRight, bottomRight
    });

    //汉化DatePicker 
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
	//载入 DatePicker
	$('#date').datepicker();
	$("#ui-datepicker-div").css('font-size','12px'); //改变字体大小

    //汉化 Timepicker
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
	

	var startTimeBox = $('#time-begin');	//开始时间文本框
	var endTimeBox = $('#time-end');		//结束时间文本框

	$('#time-begin').timepicker({
		onClose:function(){
			if(startTimeBox.val() != '' && endTimeBox.val() != ''){
				var s_time = startTimeBox.val().split(':');
				var e_time = endTimeBox.val().split(':');
				var time_error = "<div class='time_error'>选择的时间范围有误</div>";

				if (endTimeBox.val() != "结束时间") {
					if(e_time[0] < s_time[0]){				//小时对比
						if(!$(".time_error").is(":visible")){
							$("#time-end").after(time_error);
							$(".time_error").fadeIn().fadeOut().fadeIn().fadeOut().fadeIn();	//闪烁效果
						}else{
							$(".time_error").fadeOut().fadeIn().fadeOut().fadeIn().fadeOut().fadeIn();	//闪烁效果
						}
					}else if(e_time[0] == s_time[0]){
						if(e_time[1] <= s_time[1]){			//分钟对比
							if(!$(".time_error").is(":visible")){
								$("#time-end").after(time_error);
								$(".time_error").fadeIn().fadeOut().fadeIn().fadeOut().fadeIn();	//闪烁效果
							}else{
								$(".time_error").fadeOut().fadeIn().fadeOut().fadeIn().fadeOut().fadeIn();	//闪烁效果
							}
						}
					}else{
						if($(".time_error")){		//去除显示
							$(".time_error").remove();
						}
					}
				}
			}
			//存在错误防止提交
			$("#frmNew").submit(function(e) {
				if($(".time_error").is(":visible")){
					//e.preventDefault();
					$('body,html').animate({scrollTop:0},500);
					$(".time_error").fadeIn().fadeOut().fadeIn().fadeOut().fadeIn();	//闪烁效果
					return false;
				}else{
					return true;
				}
			});
		},	
		onSelect: function (){
			if($(".time_error")){		//去除显示
				$(".time_error").remove();
			}
		},
		hourGrid: 4,
		minuteGrid: 30,
		stepHour: 1,
		stepMinute: 10,
	});


	$('#time-end').timepicker({
		onClose:function(){
			if(startTimeBox.val() != '' && endTimeBox.val() != ''){
				var s_time = startTimeBox.val().split(':');
				var e_time = endTimeBox.val().split(':');
				var time_error = "<div class='time_error'>选择的时间范围有误</div>";
				
				if (startTimeBox.val() != "开始时间") {
					if(e_time[0] < s_time[0]){				//小时对比
						if(!$(".time_error").is(":visible")){
							$("#time-end").after(time_error);
							$(".time_error").fadeIn().fadeOut().fadeIn().fadeOut().fadeIn();	//闪烁效果
						}else{
							$(".time_error").fadeOut().fadeIn().fadeOut().fadeIn().fadeOut().fadeIn();	//闪烁效果
						}
					}else if(e_time[0] == s_time[0]){
						if(e_time[1] <= s_time[1]){			//分钟对比
							if(!$(".time_error").is(":visible")){
								$("#time-end").after(time_error);
								$(".time_error").fadeIn().fadeOut().fadeIn().fadeOut().fadeIn();	//闪烁效果
							}else{
								$(".time_error").fadeOut().fadeIn().fadeOut().fadeIn().fadeOut().fadeIn();	//闪烁效果
							}
						}
					}else{
						if($(".time_error")){		//去除显示
							$(".time_error").remove();
						}
					}
				}
			}
			//存在错误防止提交
			$("#frmNew").submit(function(e) {
				if($(".time_error").is(":visible")){
					//e.preventDefault();
					$('body,html').animate({scrollTop:0},500);
					$(".time_error").fadeIn().fadeOut().fadeIn().fadeOut().fadeIn();	//闪烁效果
					return false;
				}else{
					return true;
				}
			});
		},
		onSelect: function (){
			if($(".time_error")){		//去除显示
				$(".time_error").remove();
			}
		},
		hourGrid: 4,
		minuteGrid: 30,
		stepHour: 1,
		stepMinute: 10,				
	});

	//载入 Tagsit
    var sampleTags = ['望江', '江安', '成都', '篮球赛', '足球赛', '电影','2013','讲座','比赛','竞赛','部门','NBA','宅不起','Zhaibuqi','体育部','文艺部'];
    //this is an INPUT element, rather than a UL as in the other 
    $('#tags').tagit({
        availableTags: sampleTags
        //标签是否允许空格, allowSpaces: true
        //删除标签确认, removeConfirmation: true
    });

}); 