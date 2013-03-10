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
    var $picShow = $("<div></div>").attr('id','picCut').css({
        position:'absolute',
        width:200,
        height:165,
        display:'none',
        background:'#777'
    });
    var $headOriginDiv = $('#head-portrait-origin').parent();
    $picShow.insertBefore($headOriginDiv);
<<<<<<< HEAD
    $form.on('change','#update',function(){
=======
    $form.on('change','#update-btn',function(){
>>>>>>> 最新版本
        var file = this.files[0];
        if(file.size >= 2097152){
            console.log('too big');
        } else if(!(/^image\/.*$/.test(file.type))){
            console.log('not pic');
        } else{
<<<<<<< HEAD
=======
            console.log('success');
>>>>>>> 最新版本
            ZHAIBUQI.uploadPic.call($(this),{
                url:'php/up_portrait.php',
                submitted:submitted
            });
        }
    });

    function submitted(){
        //检测iframe是否成功接收到数据
<<<<<<< HEAD
        var complete = setInterval(function(){
            if($('#uploadTargetFrame').contents().find('#complete').length !== 0){
                clearInterval(complete);
                $('#pos,#size').val('');
                console.log($('#uploadTargetFrame').contents().find('#complete').text());
                //var $img = $('<img/>').attr('src',$('#uploadTargetFrame').contents().find('#complete').text());
                var $img = $('<img />').attr('src','images/test.png');

                var loaded = setInterval(function(){
                    if($img.get(0).complete && $img.get(0).width !== 0){
                        clearInterval(loaded);
                        $headOriginDiv.animate({marginLeft:220},60,function(){
                            $picShow.show();
                            //呈现图片
                            ZHAIBUQI.picLoaded({
                                $img:$img,
                                maxW:200,
                                maxH:165,
                                $picShow:$picShow,
                                loaded:function(){
                                    cutDiv({
                                        $img:this.$img
                                    });
                                }
                            });
                        });
                    }
                },30);
                setTimeout(function(){
                    clearInterval(loaded);
                },3000);
            }
        },5);
        setTimeout(function(){
            clearInterval(complete);
        },3000);
=======
        if($('#uploadTargetFrame').contents().find('#complete').length !== 0){
            $('#pos,#size').val('');
            console.log($('#uploadTargetFrame').contents().find('#complete').text());
            var $img = $('<img/>').attr('src',$('#uploadTargetFrame').contents().find('#complete').text());
            //var $img = $('<img />').attr('src','images/test.png');

            var loaded = setInterval(function(){
                if($img.get(0).complete && $img.get(0).width !== 0){
                    clearInterval(loaded);
                    $headOriginDiv.animate({marginLeft:220},60,function(){
                        $picShow.show();
                        //呈现图片
                        ZHAIBUQI.picLoaded({
                            $img:$img,
                            maxW:200,
                            maxH:165,
                            $picShow:$picShow,
                            loaded:function(){
                                cutDiv({
                                    $img:this.$img
                                });
                            }
                        });
                    });
                }
            },30);
            setTimeout(function(){
                clearInterval(loaded);
            },3000);
        }
>>>>>>> 最新版本
    }
    function cutDiv(options){
        //设置默认options
        var opt = {
            bgColor: '#000',
            bgOpacity: 0.5,
            aspectRatio:1,
            allowSelect:false,
            onChange:updatePreview
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
            imgHeight = opt.$img.height() || opt.$img.get(0).height;

        //求出编辑框居中的坐标
        var centerCursor = [
            Math.floor(imgWidth/4),
            Math.floor(imgHeight/4)
        ];
        centerCursor.push(centerCursor[0]*2);
        centerCursor.push(centerCursor[1]*2);

        //获取预览框
        var xOrigin = 165,
            yOrigin = 165,
            xSmall = 45,
            ySmall = 45,
            boundx,boundy,
            $originDiv = $('#head-portrait-origin').parent().css({
                width:xOrigin,
                height:xOrigin
            }),
            $smallDiv = $('#head-portrait-small').parent().css({
                width:xSmall,
                height:ySmall
            }),
<<<<<<< HEAD
            $originPic = options.$img.clone(),
            $smallPic = options.$img.clone();
=======
            $originPic = options.$img.clone().attr('id','head-portrait-origin'),
            $smallPic = options.$img.clone().attr('id','head-portrait-small');
>>>>>>> 最新版本
        $originDiv.empty().css('overflow','hidden').append($originPic);
        $smallDiv.empty().css('overflow','hidden').append($smallPic);



        //调用Jcrop
        var jcrop_api;

        opt.$img.Jcrop(opt,function(){
            jcrop_api = this;
            var bounds = jcrop_api.getBounds();
            boundx = bounds[0];
            boundy = bounds[1];
            jcrop_api.setSelect(centerCursor);
            jcrop_api.setOptions({ bgFade: true });
            jcrop_api.ui.selection.addClass('jcrop-selection');
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
        //预览图
        function updatePreview(c)
        {
            if (parseInt(c.w) > 0)
            {
                var rxOrigin = xOrigin / c.w,
                    ryOrigin = yOrigin / c.h,
                    rxSmall = xSmall / c.w,
                    rySmall = ySmall / c.h;
                $originPic.css({
                    width: Math.round(rxOrigin * boundx) + 'px',
                    height: Math.round(ryOrigin * boundy) + 'px',
                    marginLeft: '-' + Math.round(rxOrigin * c.x) + 'px',
                    marginTop: '-' + Math.round(ryOrigin * c.y) + 'px'
                });
                $smallPic.css({
                    width: Math.round(rxSmall * boundx) + 'px',
                    height: Math.round(rySmall * boundy) + 'px',
                    marginLeft: '-' + Math.round(rxSmall * c.x) + 'px',
                    marginTop: '-' + Math.round(rySmall * c.y) + 'px'
                });
                updatePicCut(jcrop_api.ui.selection,jcrop_api.ui.holder);
            }
        }
    }
});
