/**
 * Created with JetBrains WebStorm.
 * User: willkan
 * Date: 13-4-2
 * Time: 下午9:37
 * To change this template use File | Settings | File Templates.
 */
define(function(require, exports, module){
    var loading = require('loading'),
        $tips,
        selectedPerson = [];

    module.exports = {
        getCache : function() {
            return  selectedPerson;
        },
        reset: function() { selectedPerson = []; }
    };



    /*
     * @param event
     * */
    function scrollToBottom(event){
        var $target = $(this),
            wScrollTop = $target.scrollTop(),
            options = event.data,
            bottom = options.bottom || 200,//表示滚到底部以上距离
            top = options.top || 100,
            $toTop = options.$toTop || $('#to-top'),
            $doc = $(document);
        if(wScrollTop > top){
            $toTop.fadeIn(300);
        } else{
            $toTop.fadeOut(100);
        }
        if($doc.height() - $target.height() - wScrollTop < bottom && loading.currentPage()>0){
            $tips.css('visibility', 'visible');
            loading.loadingAjax(1, 'load');
        }
    }

    $(function(){
        var $contactsBody = $('#contacts-body'),
            $toTop = $('#to-top'),
            $bodyAndHtml = $('body,html');
        $tips = $('.clear');
        if($('.operation').length !== 0){
            /* 头像框选中及取消动画 */
            $contactsBody.on('click','.user-display',function(){
                var $target = $(this),
                    personValue = $target.attr('value'),
                    groupValue = $target.find('.extra').attr('value');
                $target.toggleClass('active');
                if($target.hasClass('active')){
                    selectedPerson.push({
                        personValue: personValue,
                        groupValue: groupValue
                    });
                } else{
                    selectedPerson.splice(selectedPerson.indexOf({
                        personValue: personValue,
                        groupValue: groupValue
                    }), 1);
                }
            });
            $contactsBody.on('click','.user-display a',function(event){
                event.stopPropagation();
            });
            /* 下拉菜单 */
            var $operationBox = $('#operation-box');
            $operationBox.on('mouseenter','.operation',function(){
                $(this).find('.group-selection').stop(false,true).slideDown(200);
            });
            $operationBox.on('mouseleave','.operation',function(){
                $(this).find('.group-selection').stop(false,true).slideUp(200);
                $('#edit').blur();
            });
        }
        $tips.on('click',function(){
            loading.loadingAjax(1,'load');
        });
        $toTop.css('visibility', 'visible').hide();
        $toTop.on('click', function(){
            $bodyAndHtml.animate({scrollTop:0}, 500);
        });
        $(window).on("scroll",{
            $toTop: $toTop
        },scrollToBottom);
    });
});
