/**
 * Created with JetBrains PhpStorm.
 * User: willkan
 * Date: 13-3-9
 * Time: 下午3:24
 * To change this template use File | Settings | File Templates.
 */
$(function(){
    "use strict";
    //隐藏输入，以填入切图框的位置及大小
    var $form = $("#personal-info"),
        $pos = $('<input type=text>').attr({
            'id':'pos',
            'name':'pos'
        }).hide(),
        $size = $('<input type=text>').attr({
            'id':'size',
            'name':'size'
        }).hide();
    $form.append($pos, $size);
    //选择文件事件
    var $picShow = $("#head-portrait-origin");
    $form.on('change','#update',function(){
        var file = this.files[0];
        if(file.size >= 2097152){
            console.log('too big');
        } else if(!(/^image\/.*$/.test(file.type))){
            console.log('not pic');
        } else{
            ZHAIBUQI.uploadPic.call($(this),{
                url:'php/upload_picture.php',
                submitted:submitted
            });
        }
    });
    function submitted(){
        //检测iframe是否成功接收到数据
        var complete = setInterval(function(){
            if($('#uploadTargetFrame').contents().find('#complete').length !== 0){
                clearInterval(complete);
                console.log($('#uploadTargetFrame').contents().find('#complete').text());
                //var $img = $('<img/>').attr('src',$('#uploadTargetFrame').contents().find('#complete').text());
                var $img = $('<img />').attr('src','images/test.png');
                //呈现图片
                ZHAIBUQI.picLoaded({
                    $img:$img,
                    maxW:165,
                    maxH:165,
                    $picShow:$picShow
                });
            }
        },5);
        setTimeout(function(){
            clearInterval(complete);
        },3000);
    }
});
