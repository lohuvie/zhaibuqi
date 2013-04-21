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
        $loadError = $('<div></div>').html('非常抱歉<br />图片载入失败，请重试').css({
            color:'#000',
            fontSize:31,
            position:'absolute',
            zIndex: 6000,
            textAlign: 'center',
            margin: '135px 45px'
        }).hide(),
        $wrongFormat = $('<div></div>').hide(),
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
    $loadError.insertBefore($picShow);

    //选择图片出错框
    $picNotChoose.css(errorCss).css({
        top:picShowOffset.top+360,
        left:picShowOffset.left+90
    });
    $picNotValidate.css(errorCss).css({
        top:picShowOffset.top+360,
        left:picShowOffset.left+407
    });
    $wrongFormat.css(errorCss).css({
        top:picShowOffset.top+360,
        left:picShowOffset.left+90
    });
    $(document.body).append($picNotChoose,$picNotValidate,$wrongFormat);

    //等待图标
    var loading = new CanvasLoader('canvasloader-container');
    loading.setColor('#508fc7'); // default is '#000000'
    loading.setShape('spiral'); // default is 'oval'
    loading.setDiameter(43); // default is 40
    loading.setDensity(57); // default is 40
    loading.setFPS(31); // default is 24

    //选择文件事件
    $form.on('change','#poster',function(){
        var file = this.files[0];
        ZHAIBUQI.picValidate = false;
        $picNotChoose.hide();
        $picNotValidate.hide();
        $loadError.hide();
        $wrongFormat.hide();
        if(file.size >= 2097152){
            //console.log('too big');
            $wrongFormat.text('上传图片不能大于2M').show();
        } else if(!(/^image\/.*$/.test(file.type))){
            //console.log('not pic');
            $wrongFormat.text('你上传的文件不是图片').show();
        } else{
            loading.show();
            ZHAIBUQI.uploadPic.call($(this),{
                url:'php/upload_picture.php',
                submitted:submitted
            });
        }
    });

    //图片剪切函数
    $form.submit(function(){
        var $jcropSelection = $('.jcrop-selection');
        $wrongFormat.hide();
        if($jcropSelection.length === 0 ){
            $picNotChoose.show();
            return false;
        } else if((!ZHAIBUQI.picValidate && $jcropSelection.width() !== 0)){
            $picNotValidate.show();
            return false;
        }
    });

    //图片提交函数
    function submitted(){
        //检测iframe是否成功接收到数据
        if($('#uploadTargetFrame').contents().find('#complete').length !== 0){
            //成功接收
            console.log($('#uploadTargetFrame').contents().find('#complete').text());
            var $img = $('<img/>').attr('src',$('#uploadTargetFrame').contents().find('#complete').text());
            //呈现图片
            ZHAIBUQI.picLoaded({
                $img:$img,
                $picShow:$picShow,
                loaded:function(){
                    loading.hide();
                    cutDiv({
                        $img:this.$img
                    });
                }
            });
        } else{
            //接收失败
            if(!$picShow.hasClass('none')){
                $('#picValidate').remove();
            }
            $picShow.empty();
            $loadError.show();
            loading.hide();
        }
    }

    //切图框
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
            minSize:[Math.floor(opt.minSize[0]*rateMin[0]),Math.floor(opt.minSize[1]*rateMin[1])]
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
                $wrongFormat.hide();
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









