/* 
 * User: lemonz-
 * Date: 13-4-15
 */
 
// This method is called right before the ajax form validation request
// it is typically used to setup some visuals ("Please wait...");
// you may return a false to stop the request 
function beforeCall(form, options){
    if (window.console) 
        console.log("该函数在验证规则通过后，Ajax 验证前执行");
    return true;
}

// Called once the server replies to the ajax form validation request
function ajaxValidationCallback(status, form, json, options){
    if (window.console) 
        console.log(status);
    
    if (status === true) {
        console.log("Ajax 验证通过");
        //uncomment these lines to submit the form to form.action
        //form.validationEngine('detach');
        //form.submit();
        // or you may use AJAX again to submit the data
    }
}

$(document).ready(function() {
    var $notUseBox = $('#not_use_time');        //选项框

    var $emailSuccessBox = $('.emailSuccess').parent();  //保存成功提示
    var $emailErrorBox = $('.emailError').parent();      //保存失败提示

    //验证密码输入框
    $("#password-form").validationEngine({ 
        ajaxFormValidation: true,
        onAjaxFormComplete: ajaxValidationCallback,
        onBeforeAjaxFormValidation: beforeCall,
        promptPosition:"centerRight",
        scroll:false 
    });

    //点击保存邮箱设置
    $("#mail-submit").click(function(){
        $space_time = $("input[name='not_use_time']:checked").val();
        $.ajax({ 
            type:"POST",
            url:"php/email_settings.php", 
            datatype:"json",
            data:{not_use_time:$space_time},
            success:function(data){
                var stime = data.space_time;
                if(stime === -1){      //用户未登录
                    location.href = "login.php"; 
                    console.log("用户未登录");
                }else if(stime === -2){     //数据库出错
                    console.log("数据库出错");
                }else if(stime === -3){      //保存失败
                    $emailSuccessBox.hide();
                    $ErrorTips.hide().fadeIn(400);
                    console.log("邮箱设置保存失败");
                }else if(stime === 1){      //保存成功
                    $emailErrorBox.hide();
                    $emailSuccessBox.hide().fadeIn(400);
                    console.log("邮箱设置保存成功");
                }else{
                    console.log("其他");
                }
            }
        });
    });

    //点击提示框消失
    $('#mail-fiels div').click(function(){
        $(this).parent().fadeOut(200);
    });

});