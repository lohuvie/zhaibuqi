/* 
 * User: lemonz-
 * Date: 13-3-12
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
        // uncomment these lines to submit the form to form.action
        //form.validationEngine('detach');
        //form.submit();
        // or you may use AJAX again to submit the data
    }
}
$(document).ready(function() {
    //验证密码输入框
    $("#password-form").validationEngine({ 
        ajaxFormValidation: true,
        onAjaxFormComplete: ajaxValidationCallback,
        onBeforeAjaxFormValidation: beforeCall,
        promptPosition:"centerRight",
        scroll:false 
    })
});