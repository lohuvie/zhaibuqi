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
    ZHAIBUQI.picValidate = false;//已确认切图框
})();

$(function(){
    "use strict";
    //隐藏输入，以填入切图框的位置及大小
    var $form = $("#frmNew"),
        $pos = $('<input type=text>').attr({
            'id':'pos',
            'name':'pos'
        }).val('0,0').hide(),
        $size = $('<input type=text>').attr({
            'id':'size',
            'name':'size'
        }).val('1,1').hide(),
        $picShow = $("#upload-pic"),
        $picNotChoose = $('<div></div>').text('请选择图片').hide(),
        $picNotValidate = $('<div></div>').text('请确认修改尺寸范围').hide(),
        errorCss = {
            background: '#393939',
            border: '2px solid #ddd',
            borderRadius: '6px',
            boxShadow: '0 0 6px #000',
            color: '#fff',
            font: '12px/1.7 "宋体b8b\\4f53",Tahoma',
            padding: '4px 10px 4px 10px',
            position: 'absolute',
            width: 120,
            zIndex: 5001
        },
        picShowOffset = $picShow.offset();


    $form.append($pos, $size);

    //图片出错框
    $picNotChoose.css(errorCss).css({
        top:picShowOffset.top+355,
        left:picShowOffset.left-160
    });
    $picNotValidate.css(errorCss).css({
        top:picShowOffset.top+355,
        left:picShowOffset.left+420
    });
    $(document.body).append($picNotChoose,$picNotValidate);

    //选择文件事件
    $form.on('change','#poster',function(){
        var file = this.files[0];
        ZHAIBUQI.picValidate = false;
        $picNotChoose.hide();
        $picNotValidate.hide();
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

    //图片剪切函数
    $form.submit(function(){
        var $jcropSelection = $('.jcrop-selection');
        if($jcropSelection.length === 0 ){
            $picNotChoose.show();
            $('body,html').animate({scrollTop:$picNotChoose.offset().top},50);
            return false;
        } else if((!ZHAIBUQI.picValidate && $jcropSelection.width() !== 0)){
            $picNotValidate.show();
            $('body,html').animate({scrollTop:$picNotValidate.offset().top},50);
            return false;
        }
    });

    //图片提交函数
    function submitted(){
        //检测iframe是否成功接收到数据
        if($('#uploadTargetFrame').contents().find('#complete').length !== 0){
            console.log($('#uploadTargetFrame').contents().find('#complete').text());
            var $img = $('<img/>').attr('src',$('#uploadTargetFrame').contents().find('#complete').text());
            //呈现图片
            ZHAIBUQI.picLoaded({
                $img:$img,
                $picShow:$picShow,
                loaded:function(){
                    cutDiv({
                        $img:this.$img
                    });
                }
            });
        }
    }

    function cutDiv(options){
        //设置默认options
        var opt = {
            minSize: [215,150],
            bgColor: '#fff',
            bgOpacity: 0.5,
            allowSelect: false,
            allowResize:false,
            aspectRatio:0
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
            rateMin = [1,1];
        if(imgWidth < opt.minSize[0]){
            rateMin[0] = imgWidth/opt.minSize[0];
        } else if(imgHeight < opt.minSize[1]){
            rateMin[1] = imgHeight/opt.minSize[1];
        }
        $.extend(opt,{
            minSize:[Math.floor(opt.minSize[0]*rateMin[0]),Math.floor(opt.minSize[1]*rateMin[1])],
        });

        //求出编辑框居中的坐标
        var centerCursor = [
            Math.floor((imgWidth-opt.minSize[0])/2),
            Math.floor((imgHeight-opt.minSize[1])/2)
        ];
        centerCursor.push(centerCursor[0]+opt.minSize[0]);
        centerCursor.push(centerCursor[1]+opt.minSize[1]);


        //调用Jcrop
        var jcrop_api;

        opt.$img.Jcrop(opt,function(){
            jcrop_api = this;
            //jcrop_api.setSelect(centerCursor);
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
                ZHAIBUQI.picValidate = true;
                console.log('pos',$('#pos').val());
                console.log('size',$('#size').val());
            }
        }

        //建立确认剪切按钮
        function createValidate(){
            var $btn = $('#picValidate'),
                $pos = $('#pos'),
                $size = $('#size');
            if($btn.length === 0){
                $btn = $('<span></span>').attr('id','picValidate').css({
                    marginTop: 10,
                    marginRight: 3,
                    padding: '4px',
                    background: '#bbb',
                    textAlign: 'center',
                    float: 'right',
                    width: 76,
                    color: '#FFF',
                    cursor: 'pointer'
                });
                $btn.insertBefore($('.upload'));
            }
            $pos.val('0,0');
            $size.val('1,1');
            //清空pos,size输入框
            $btn.off();
            $btn.click(function(){
                if($btn.text() === '确定'){
                    jcrop_api.setOptions({
                        bgOpacity:0,
                        allowMove:false,
                        allowResize:false
                    });
                    $picNotValidate.hide();
                    $btn.text('修改尺寸');
                    $btn.css('background','#bbb');
                    updatePicCut(jcrop_api.ui.selection,jcrop_api.ui.holder);
                } else{
                    jcrop_api.setOptions({
                        bgOpacity:opt.bgOpacity,
                        allowMove:true
                    });
                    $btn.text('确定');
                    $btn.css('background','#84c43c');
                    if(jcrop_api.ui.selection.width() === 0){
                        jcrop_api.setSelect(centerCursor);
                    }
                    jcrop_api.setOptions({
                        allowMove:true,
                        allowResize:true
                    });
                    ZHAIBUQI.picValidate = false;
                }
            });
            $btn.css('background','#bbb');
            $btn.text('修改尺寸');
        }
    }
});









