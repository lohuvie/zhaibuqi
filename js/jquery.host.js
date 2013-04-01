/* 
 * User: lemonz-
 * Date: 13-3-31
 */
 
$(document).ready(function() {
    //验证输入框
    $("#apply-form").validationEngine({ 
        promptPosition:"centerRight",
        scroll:false 
    });

    //指定管理员 
    $("#not-self").click(function() {   //点击非本人
        $("#extra").show(700);
    });

    $("#self").click(function() {   //默认点击本人    
        $("#extra").hide(500); 
    });
});