/**
* jquery.comment.js
**/
$(function(){
    'use strict';
    var $arrowBox = $('<div></div>').addClass('arrow-box').append($('<div></div>').addClass('arrow')),
        $replyBtn = $('<span></span>').addClass('reply-btn').text('回复'),
        $postBox = $('#post-box'),
        $commentContent = $('.comment-content'),
        $commentForm = $('#comment-form'),
        $unfold = $('.unfold'),
        $tips = $('.tips'),
        pageId = location.search.match(/(id|activity)=.*(&|$)/)[0].split('=')[1],
        pageType = /personal-page/.test(location.pathname) ? 'p' : 'a',
        login = $('#login').length === 0;
    /*显示回复按钮*/
    $postBox.on('mouseenter','.user-post',function(){
        $(this).find('.reply-btn').fadeIn(200);
    });
    $postBox.on('mouseleave','.user-post',function(){
        $(this).find('.reply-btn').fadeOut(200);
    });
    /*按下回复按钮*/
    $postBox.on('click','.reply-btn',function(){
        var $target = $(this),
            $userPost = $target.parent(),
            replyeeName = '回复' + $userPost.find('.user-name').text() + ':',
            curPos = replyeeName.length;
        $commentContent.val(replyeeName);
        $commentContent.data('replyeeId', $target.data('userId'));
        $commentContent.data('replyeeName', $target.prevAll('a').text());
        setSelectionRange($commentContent.get(0),curPos,curPos);

        //选择文字范围
        function setSelectionRange(input, selectionStart, selectionEnd){
            if(input.setSelectionRange){
                input.focus();
                input.setSelectionRange(selectionStart,selectionEnd);
            } else if(input.createTextRange){
                var range = input.createTextRange();
                range.collapse(true);
                range.moveEnd('character',selectionEnd);
                range.moveStart('character',selectionStart);
                range.select();
            }
        }
    });
    $commentContent.focus(function(){
        $tips.hide();
    });
    /*提交评论内容*/
    $commentForm.submit(function(event){
        var action = $commentForm.attr('action'),
            data = {pageId: pageId},
            replyeeId = $commentContent.data('replyeeId'),
            replyeeName = $commentContent.data('replyeeName'),
            content = $commentContent.val().replace(/^\s*/,''),
            reg, replyee, comment;
        if(!login){
            $tips.text('请登录后再提交内容').css('color','red').fadeIn().fadeOut().fadeIn();
            return false;
        }
        //判断是否有回复人
        if(content.match(/[^\s]/)){
            reg = /^回复[^\s]+:/;
            replyee = content.match(reg);
            comment = content.replace(reg,'');
            if(replyeeId && replyee && replyee[0].substr(2, replyeeName.length) === replyeeName){
                data.replyeeId = replyeeId;
                if(comment.match(/[^\s]/)){
                    data.comment = comment.replace(/^\s*/,'');
                }
            } else{
                data.comment = content;
            }
        }
        //判断回复内容是否为空
        if(data.comment){
            var timer = textLoading($tips,'正在提交',600);
            $tips.css('color', '#444').show();
            timer.start();
            $.ajax( {
                    url:action ,
                    type:'POST',
                    data:data,
                    dataType: 'json',
                    success:function(data){
                        $commentContent.val('');
                        addOneComment(data.comment[0]).hide().appendTo($postBox).fadeIn(300);
                        timer.stop();
                    },
                    error: function(data){
                        if(data.status === 401){
                            timer.stop();
                            $tips.text('请登录后再提交内容').css('color','red').fadeIn().fadeOut().fadeIn();
                            return false;
                        }
                        timer.stop();
                        $tips.text('评论失败，请刷新页面重试').css('color','red').fadeIn().fadeOut().fadeIn();
                    }
                }
            );
        } else{
            $tips.text('评论内容不能为空').css('color','red').fadeIn().fadeOut().fadeIn();
        }
        event.preventDefault();
        event.stopPropagation();
    });

    /*展开评论*/
    $unfold.on('click',function(){
        var timer = textLoading($unfold, '正在加载', 600);
        timer.start();
        /*加载评论*/
        $.ajax({
            url:'comment_more.php',
            data: {
                getMore: 1,
                pageId: pageId,
                pageType: pageType
            },
            dataType:'json',
            type:'GET',
            success: function(data){
                var comment = data.comment,
                    $insertPost = $('.user-post:first');
                timer.stop();
                $unfold.hide();
                if(comment.length !== 0){
                    $.each(comment,function(){
                        var $userPost = addOneComment(this).hide();
                        $userPost.insertAfter($insertPost);
                        $insertPost = $userPost;
                        $userPost.fadeIn(300);
                    });
                }
            },
            error: function(){
                timer.stop();
                $unfold.text('加载失败，请再次点击重试');
            }
        });
    });
    /*添加一个评论*/
    function addOneComment(options){
        var user = options.userName,
            userLink = options.userLink,
            replyTime = options.replyTime,
            replyee= options.replyee,
            replyeeLink = options.replyeeLink,
            content = options.content,
            userPhoto = options.userPhoto,
            _$arrowBox = $arrowBox.clone(),
            _$replyBtn = $replyBtn.clone().data('userId', options.userId),
            $userPost = $('<li></li>').addClass('user-post'),
            $photo = $('<a></a>').attr('href',userLink).append($('<img/>').attr('src',userPhoto).addClass('user-photo')),
            $replyDetail = $('<div></div>').addClass('reply-detail'),
            $commentHeader = $('<h3></h3>').addClass('comment-header'),
            $userName = $('<a></a>').attr('href',userLink).addClass('user-name').text(user),
            $replyTime = $('<span></span>').addClass('reply-time').text(replyTime),
            $content = $('<p></p>').addClass('content'),
            $replyee;
        $commentHeader.append($userName,$replyTime,_$replyBtn);
        if(replyee){
            $replyee = $('<a></a>').addClass('replyee-name').text(replyee).attr('href',replyeeLink);
            $content.append('回复',$replyee,':'+content);
        } else{
            $content.append(content);
        }
        $replyDetail.append($commentHeader,$content);
        $userPost.append($photo,_$arrowBox,$replyDetail);
        return $userPost;
    }
    /*使文字出现...的加载动画*/
    function textLoading($text,textPre,interval){
        var timer;
        $text.text(textPre + '...');
        return {
            start: function(){
                timer = setInterval(function(){
                    var pre= textPre + '.',
                        dotCount = $text.text().replace(/^[^\.]+\./,'').length;
                    switch (dotCount){
                        case 0:
                            $text.text(pre + '.');
                            break;
                        case 1:
                            $text.text(pre + '..');
                            break;
                        case 2:
                            $text.text(pre);
                            break;
                        default:
                            $text.text(pre + '..');
                            break;
                    }
                },interval || 800);

            },
            stop: function(){
                clearInterval(timer);
            }
        };
    }
});