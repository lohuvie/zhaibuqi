/**
 * Created with JetBrains WebStorm.
 * User: willkan
 * Date: 13-3-30
 * Time: 下午11:23
 * To change this template use File | Settings | File Templates.
 */
define(function(require, exports, module){
    var firstQueue = {},
        lastQueue = {};
    module.exports = queueAjax;
    /*
     * @param options {object} options的格式与jQuery.ajax(options)里的options一样
     * @param setting {number} (同一时间内）-1:只执行最后一次处理函数,1:只发送第一次请求，默认为0:$.ajax
     * @param name {string} ajax请求类名（假若某类ajax存在请求中的ajax，则不允许再向服务器请求同类ajax）
     * */
    function queueAjax(options, setting, name){
        var LAST = -1,
            FIRST = 1,
            DEFAULT = 0,
            namespace = name || 'default',
            currentRequest,
            ajax = options;
        switch(setting){
            case FIRST:
                //return function
                if(!firstQueue[namespace]){
                    if(ajax && ajax.url){
                        firstQueue[namespace] = true;
                        for(var i in ajax){
                            if(ajax.hasOwnProperty(i) && typeof(ajax[i]) === 'function'){
                                ajax[i] = firsten(ajax[i], namespace);
                            }
                        }
                        $.ajax(ajax);
                    }
                }
                break;
            case LAST:
                //return boolean
                currentRequest = (new Date()).valueOf();
                if(ajax && ajax.url){
                    lastQueue[namespace] = currentRequest;
                    for(var j in ajax){
                        if(ajax.hasOwnProperty(j) && typeof(ajax[j]) === 'function'){
                            ajax[j] = lasten(ajax[j], currentRequest, namespace);
                        }
                    }
                    $.ajax(ajax);
                }
                break;
            default :
                $.ajax(ajax);
        }
    }
    function firsten(fn, namespace){
        return function(){
            fn.apply($,arguments);
            firstQueue[namespace] = false;
        };
    }
    function lasten(fn, currentRequest, namespace){
        return function(){
            if(currentRequest === lastQueue[namespace]){
                fn.apply($,arguments);
            }
        };
    }
});
