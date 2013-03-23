$(function(){
    "use strict";
    var timer = setInterval(function(){
        var $waiting = $('#waiting'),
            time = parseInt($waiting.text(),10);
        $waiting.text(time - 1);
        if(time - 1 === 0){
            location.href = 'login.php';
        }
    },1000);
});