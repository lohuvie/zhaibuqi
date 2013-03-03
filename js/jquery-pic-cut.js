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
                width: width,
                height: height
            });
            //插入图片
            if($picShow.hasClass("none")){
                $picShow.removeClass("none");
                $picShow.css({
                    paddingTop:Math.floor((max_h - height)/2),
                    paddingLeft:Math.floor((max_w - width)/2)
                });
            }
            $picShow.empty();
            $picShow.append($img);
        }
    }
    window.ZHAIBUQI.picLoaded = picLoaded;

    function
})();

$(function(){
    "use strict";
    var $uploadBtn = $("#poster"),
        $picShow = $("#upload-pic");
    $uploadBtn.on("change",function(){
        ZHAIBUQI.picLoaded($picShow,$uploadBtn);
    });
});
