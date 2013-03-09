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
    var $picShow = $("#upload-pic");
    $form.on('change','#poster',function(){
        if(/.+\.(jpg|jpeg|png|gif)$/.test($(this).val())){
            ZHAIBUQI.uploadPic.call($(this),{
                url:'php/upload_picture.php',
                submitted:submitted
            });
        } else{
            console.log('error');
        }
    });
});
