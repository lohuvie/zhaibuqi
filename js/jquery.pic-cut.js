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

    function cutDiv(options){
        //设置默认options
        var opt = {
            maxSize: [235,350],
            minSize: [235,200],
            bgColor: '#000',
            bgOpacity: 0.5,
            allowSelect: false,
            aspectRatio:0,
            createHandles:null,
            createDragbars:['n','s']
        };

        //按options修改opt
        if (typeof(options) !== 'object') {
            options = {};
        }
        opt = $.extend(opt, options);
        $.each(['onChange','onSelect','onRelease','onDblClick'],function(i,e) {
            if (typeof(options[e]) !== 'function') {
                options[e] = function () {};
            }
        });

        //按比例修改编辑框的边界
        var imgWidth = opt.$img.width() || opt.$img.get(0).width,
            imgHeight = opt.$img.height() || opt.$img.get(0).height,
            rateMin = [1,1],
            rateMax = [1,1];
        if(imgWidth < opt.minSize[0]){
            rateMin[0] = imgWidth/opt.minSize[0];
        } else if(imgHeight < opt.minSize[1]){
            rateMin[1] = imgHeight/opt.minSize[1];
        }
        if(imgWidth < opt.maxSize[0]){
            rateMax[0] = imgWidth/opt.maxSize[0];
        } else if(imgHeight < opt.maxSize[1]){
            rateMax[1] = imgHeight/opt.maxSize[1];
        }
        $.extend(opt,{
            minSize:[Math.floor(opt.minSize[0]*rateMin[0]),Math.floor(opt.minSize[1]*rateMin[1])],
            maxSize:[Math.floor(opt.maxSize[0]*rateMax[0]),Math.floor(opt.maxSize[1]*rateMax[1])]
        });

        //求出编辑框居中的坐标
        var centerCursor = [
            Math.floor((imgWidth-opt.minSize[0])/2,10),
            Math.floor((imgHeight-opt.minSize[1])/2,10)
        ];
        centerCursor.push(centerCursor[0]+opt.minSize[0]);
        centerCursor.push(centerCursor[1]+opt.minSize[1]);


        //调用Jcrop
        var jcrop_api;

        opt.$img.Jcrop(opt,function(){
            jcrop_api = this;
            jcrop_api.setSelect(centerCursor);
            jcrop_api.setOptions({ bgFade: true });
            jcrop_api.ui.selection.addClass('jcrop-selection');
            createValidate();
        });


        //切图框更新函数
        function updatePicCut($selection,$holder){
            if($holder.length !== 0 && $selection.length !== 0){
                var selectWidth = $selection.width(),
                    selectHeight = $selection.height(),
                    selectTop = parseInt($selection.css('top'),10),
                    selectLeft = parseInt($selection.css('left'),10),
                    holderWidth = $holder.width(),
                    holderHeight = $holder.height(),
                    size,pos;
                size = [(selectWidth/holderWidth).toFixed(2) , (selectHeight/holderHeight).toFixed(2)];
                pos = [(selectTop/holderHeight).toFixed(2) , (selectLeft/holderWidth).toFixed(2)];
                $('#pos').val(pos);
                $('#size').val(size);
                console.log('pos',$('#pos').val());
                console.log('size',$('#size').val());
            }
        }

        //建立确认剪切按钮
        function createValidate(){
            var $btn = $('#picValidate');
            if($btn.length === 0){
                $btn = $('<span></span>').attr('id','picValidate').css({
                    marginTop: 10,
                    marginRight: 3,
                    padding: '4px',
                    background: '#84c43c',
                    textAlign: 'center',
                    float: 'right',
                    width: 76,
                    color: '#FFF',
                    cursor: 'pointer'
                });
                $btn.insertBefore($('.upload'));
            }
            //清空pos,size输入框
            $('#pos,#size').val('');
            $btn.off();
            $btn.click(function(){
                if($btn.text() === '确定'){
                    jcrop_api.setOptions({
                        bgOpacity:0,
                        allowMove:false
                    });
                    $btn.text('重新选择');
                    $btn.css('background','#bbb');
                    updatePicCut(jcrop_api.ui.selection,jcrop_api.ui.holder);
                } else{
                    jcrop_api.setOptions({
                        bgOpacity:opt.bgOpacity,
                        allowMove:true
                    });
                    $btn.text('确定');
                    $btn.css('background','#84c43c');
                    $('#pos,#size').val('');
                }
            });
            $btn.css('background','#84c43c');
            $btn.text('确定');
        }
    }
    window.ZHAIBUQI.cutDiv = cutDiv;

    function uploadPic(url,submitted){
        var $oldDiv = $('#frameDiv');
        if($oldDiv){
            $oldDiv.remove();
        }
        console.log($(this).val());
        $(this).clone().insertAfter($(this));
        var $ajaxForm = $('<form></form>').attr({
                action: url,
                method: 'post',
                enctype:'multipart/form-data',
                target:'uploadTargetFrame'
            }),
            $frameDiv = $('<div></div>').attr('id','frameDiv'),
            $uploadTargetFrame = $('<iframe></iframe>').attr({
                id:'uploadTargetFrame',
                name:'uploadTargetFrame'
            });
        $frameDiv.append($uploadTargetFrame,$ajaxForm.append($(this)));
        $frameDiv.appendTo($(document.body));
        $frameDiv.hide();
        $ajaxForm.submit(submitted);
        $ajaxForm.trigger('submit');
    }
    window.ZHAIBUQI.uploadPic = uploadPic;

})();

$(function(){
    "use strict";
    var $form = $("#frmNew"),
        $picShow = $("#upload-pic");
    //隐藏输入，以填入切图框的位置及大小
    var $pos = $('<input type=text>').attr({
            'id':'pos',
            'name':'pos'
        }).hide(),
        $size = $('<input type=text>').attr({
            'id':'size',
            'name':'size'
        }).hide();
    $form.append($pos, $size);
    //选择文件事件
    $form.on('change','#poster',function(){
        if(/.+\.(jpg|jpeg|png|gif)$/.test($(this).val())){
            ZHAIBUQI.uploadPic.call($(this),'php/upload_picture.php',submitted);
        } else{
            console.log('error');
        }
    });
    //图片提交函数
    function submitted(){
        //检测iframe是否成功接收到数据
        var complete = setInterval(function(){
            if($('#uploadTargetFrame').contents().find('#complete').length !== 0){
                clearInterval(complete);
                console.log($('#uploadTargetFrame').contents().find('#complete').text());
                var $img = $('<img/>').attr('src',$('#uploadTargetFrame').contents().find('#complete').text());
                //呈现图片
                ZHAIBUQI.picLoaded({
                    $img:$img,
                    $picShow:$picShow,
                    loaded:function(){
                        ZHAIBUQI.cutDiv({
                            $img:this.$img
                        });
                    }
                });
            }
        },5);
        setTimeout(function(){
            clearInterval(complete);
        },3000);
    }

});









