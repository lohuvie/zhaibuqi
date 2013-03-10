/**
 * Created with JetBrains PhpStorm.
 * User: willkan
 * Date: 13-3-10
 * Time: 下午3:56
 * To change this template use File | Settings | File Templates.
 */
$(function(){
    'use strict';
    var $currentPassword = $('#current-password'),
        old,requesting;
    $currentPassword.blur(function(){
        var password = $(this).val();
        if(password !== old && requesting === 0){
            if(password.length >= 6){
                requesting = 1;
                //等待
                $.ajax({
                    url:'php/checkPassword.php',
                    data:{'current-password':password},
                    type:'POST',
                    success:function(data){
                        if(data.confirm === 'yes'){
                            //对
                            console.log('yes');
                        } else{
                            //错
                            console.log('no');
                        }
                        requesting = 0;
                    },
                    error:function(){
                        console.log('error');
                        requesting = 0;
                    }
                });
            } else{
                console.log('password < 6 or requesting');
            }
            old = password;
        }
    });

});
