/* Created with JetBrains PhpStorm.
 * User: willkan
 * Date: 13-3-2
 * Time: 下午3:50
 * To change this template use File | Settings | File Templates.
 */

$(function(){
    "use strict";
    //隐藏输入，以填入切图框的位置及大小
    var $form = $("#frmNew"),
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
});









