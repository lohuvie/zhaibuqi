/* Created with JetBrains PhpStorm.
 * User: willkan
 * Date: 13-3-2
 * Time: 下午3:50
 * To change this template use File | Settings | File Templates.
 */
(function(){
    "use strict";
    if(!window.ZHAIBUQI){
        window.ZHAIBUQI = {};
    }

    function picLoaded($picShow,$uploadBtn,max_w,max_h){
        var $img = $("<img/>");
        var url = "images/search-pic.png";
        $img.attr("src",url);
        if($img.get(0).complete && $img.get(0).width !== 0){
            var width,
                height,
                resizeRate = 1,
                imgRate = $img.get(0).height / $img.get(0).width;
            max_w = max_w || 400;
            max_h = max_h || 350;
            //调整宽高
            if($img.width <= $img.height){
                height = max_h;
                width = Math.floor(height / imgRate);
                if(width > max_w){
                    resizeRate = max_w / width;
                }
            } else{
                width = max_w;
                height = Math.floor(width * imgRate);
                if(height > max_h){
                    resizeRate = max_h / height;
                }
            }
            width = Math.floor(width * resizeRate);
            height = Math.floor(height * resizeRate);
            $img.css({
                position:'absolute',
                width: width,
                height: height,
                top:Math.floor((max_h - height)/2),
                left:Math.floor((max_w - width)/2)
            });
            //插入图片
            if($picShow.hasClass("none")){
                $picShow.removeClass("none");
                $picShow.css({
                });
            }
            $picShow.empty();
            $picShow.append($img);
            ZHAIBUQI.cutDiv.call($picShow,$img);
        }
    }
    window.ZHAIBUQI.picLoaded = picLoaded;

    function cutDiv($img){
        //创建图像副本
        var $image = $img.clone();
        //创建阴影层
        var shadeCSS = {
                height:$image.height(),
                width:$image.width(),
                opacity:0.7,
                zIndex:10000,
                filter:'alpha(opacity=70)',
                backgroundColor:'#5db2ff',
                position:'absolute',
                top:$image.css('top'),
                left:$image.css('left')
            },
            $shade = $("<div></div>").addClass('shade').css(shadeCSS);
        $shade.appendTo($(this));
        //创建编辑器
        //添加
    }
    window.ZHAIBUQI.cutDiv = cutDiv;

})();

$(function(){
    "use strict";
    var $uploadBtn = $("#poster"),
        $picShow = $("#upload-pic");
    $uploadBtn.on("change",function(){
        ZHAIBUQI.picLoaded($picShow,$uploadBtn);
    });
});
