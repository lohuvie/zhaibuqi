

(function($) {
	$.fn.validationEngineLanguage = function() {};
	$.validationEngineLanguage = {
		newLang: function() {
			$.validationEngineLanguage.allRules = 	{"required":{    			
						"regex":"none",
						"alertText":"* 此处不能为空",
						"alertTextCheckboxMultiple":"* 请选择一个选项",
						"alertTextCheckboxe":"* 此选择框为必选"},
					"length":{
						"regex":"none",
						"alertText":"* 请输入 ",
						"alertText2":" 至 ",
						"alertText3": " 位字符"},
					"maxCheckbox":{
						"regex":"none",
						"alertText":"* 复选框超过最大可选数"},	
					"minCheckbox":{
						"regex":"none",
						"alertText":"* 请至少选择 ",
						"alertText2":" 项"},	
					"confirm":{
						"regex":"none",
						"alertText":"* 您的输入有误，请重新输入"},		
					"telephone":{
						//"regex":"/^[0-9\-\(\)\ ]+$/",
						"regex":"\d{3}-\d{8}|\d{4}-\d{7}|\d{8}|\d{7}",
						"alertText":"* 您输入的电话号码格式有误"},	
					"mobilenumber":{
						"regex":"^1[358]\d{9}$|^1060[1-9]\d{1,2}\d{7,8}$",
						"alertText":"* 您输入的手机号码格式有误"},
					"email":{
						"regex":"/^[a-zA-Z0-9_\.\-]+\@([a-zA-Z0-9\-]+\.)+[a-zA-Z0-9]{2,4}$/",
						"alertText":"* 请您输入正确的电子邮件地址"},	
					"date":{
                         "regex":"/^[0-9]{4}\-\[0-9]{1,2}\-\[0-9]{1,2}$/",
                         "alertText":"* 您输入的时间格式有误，时间格式为：YYYY-MM-DD"},
					"onlyNumber":{
						"regex":"/^[0-9\ ]+$/",
						"alertText":"* 请输入数字"},	
					"noSpecialCaracters":{
						"regex":"/^[0-9a-zA-Z]+$/",
						"alertText":"* 请去掉特殊字符，重新输入"},
					"nickname":{
						"regex":"/^([\u4e00-\u9fa5][\ufe30-\uffa0]|[a-za-z0-9_])*$/",
						"alertText":"* 昵称必须是中文、字母、数字或下划线"},	
					"ajaxUser":{
						"file":"validateUser.php",
						"extraData":"name=eric",
						"alertTextOk":"* 该用户名可用",	
						"alertTextLoad":"* 验证中，请稍后……",
						"alertText":"* 该用户名已存在"},	
					"ajaxName":{
						"file":"validateUser.php",
						"alertText":"* 该用户名已存在",
						"alertTextOk":"* 该用户名可用",	
						"alertTextLoad":"* 验证中，请稍后……"},		
					"onlyLetter":{
						"regex":"/^[a-zA-Z\ \']+$/",
						"alertText":"* 请输入英文字符"}
					}	
		}
	}
})(jQuery);

$(document).ready(function() {	
	$.validationEngineLanguage.newLang()
});