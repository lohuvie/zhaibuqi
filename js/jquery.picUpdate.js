/**
 * Created with JetBrains PhpStorm.
 * User: willkan
 * Date: 13-3-9
 * Time: 下午3:09
 * To change this template use File | Settings | File Templates.
 */
(function(){
    'use strict';
    if(!window.ZHAIBUQI){
        window.ZHAIBUQI = {};
    }
    /*options: {
     $img:,
     $picshow:,
     maxW:,
     maxH:,
     loaded
     }
     **/
    function picLoaded(options){
        var loaded = setInterval(function(){
            if(options.$img.get(0).complete && options.$img.get(0).width !== 0){
                clearInterval(loaded);
                var width,
                    height,
                    resizeRate = 1,
                    imgRate = options.$img.get(0).height / options.$img.get(0).width;
                options.maxW = options.maxW || 400;
                options.maxH = options.maxH || 350;
                //调整宽高
                if(options.$img.width <= options.$img.height){
                    height = options.maxH;
                    width = Math.floor(height / imgRate);
                    if(width > options.maxW){
                        resizeRate = options.maxW / width;
                    }
                } else{
                    width = options.maxW;
                    height = Math.floor(width * imgRate);
                    if(height > options.maxH){
                        resizeRate = options.maxH / height;
                    }
                }
                width = Math.floor(width * resizeRate);
                height = Math.floor(height * resizeRate);
                //Jcrop不支持出现在绝对定位的图片的原位置，创建$container以使图片相对定位
                var $container = $('<div></div>').css({
                    position:'absolute',
                    width: width,
                    height: height,
                    top:Math.floor((options.maxH - height)/2),
                    left:Math.floor((options.maxW - width)/2)
                });
                options.$img.css({
                    width: width,
                    height: height
                });
                //插入图片
                if(options.$picShow.hasClass("none")){
                    options.$picShow.removeClass("none");
                }
                options.$picShow.empty();
                options.$picShow.append($container.append(options.$img));
                //编辑图片
                if(typeof (options.loaded) === 'function'){
                    setTimeout(function(){
                        options.loaded();
                    },30);
                }
            }
        },5);
        setTimeout(function(){
            clearInterval(loaded);
        },3000);
    }
    window.ZHAIBUQI.picLoaded = picLoaded;

    /*
    * @param options{ url:提交的目标地址,
    *                 submitted:提交成功后的处理函数}
    * */
    function uploadPic(options){
        var $uploadTargetFrame = $('#uploadTargetFrame');
        if($uploadTargetFrame.length === 0){
            $uploadTargetFrame = $('<iframe></iframe>').attr({
                id:'uploadTargetFrame',
                name:'uploadTargetFrame'
            }).hide();
            $uploadTargetFrame.appendTo($(document.body));
        }
        console.log($(this).val());
        $(this).clone().insertAfter($(this));
        var $picForm = $('#picForm');
        if($picForm.length === 0){
            $picForm = $('<form></form>').attr({
                id:'picForm',
                action: options.url,
                method: 'post',
                enctype:'multipart/form-data',
                target:'uploadTargetFrame'
            }).hide();
        }
        $picForm.empty();
        $(document.body).append($picForm.append($(this)));
        $picForm.submit(function(){
            $uploadTargetFrame.off();
            $uploadTargetFrame.on('load',options.submitted);
        });
        $picForm.trigger('submit');
    }
    window.ZHAIBUQI.uploadPic = uploadPic;
})();
