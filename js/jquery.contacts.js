/* Created with JetBrains PhpStorm.
 * User: willkan
 * Date: 12-12-9
 * Time: 下午3:50
 * To change this template use File | Settings | File Templates.
 */
var ZHAIBUQI = {
    $tips : $("#tips"),
    $dustbin : $("#dustbin"),
    $contacsBody : $("#contacts-body"),
    tipsHtml : function(str,$a){
        "use strict";
        ZHAIBUQI.$tips.html(str);
        if($a !== undefined){
            $a.appendTo(ZHAIBUQI.$tips);
        }
    },
    $cache : $("<div></div>"),
    $loadingTips : $("<p></p>").attr('id','loadingtips').css({
        "clear":"both",
        "text-align" :"center",
        "background":"#CCC",
        "padding":"5px 10px"
    }),
    $clearLeft : $("<div></div>").css("clear","left"),//清空每个user-display的浮动
    $overflow : $(".clear-left"),//清空contacts-body的浮动
    $edit : $("<input />").attr({
        "type":"text",
        "id":"edit"
    }),
    $ulFrame : $("<div></div>").append($("<p></p>")).append($("<ul></ul>")),
    end : "",
    dbDelay : 300,
    click : false,
    clickTimer : null,
    searching : "",
    page: 1,
    url: 'json/attention_list.php' + location.search,
    loading : false,
    tipsShow : function(){
        "use strict";
        ZHAIBUQI.$tips.stop(false,true).fadeIn(500);
    },
    tipsHide : function(){
        "use strict";
        ZHAIBUQI.$tips.stop(false,true).fadeOut(2000);
    },
    loadingShow : function(){
        "use strict";
        ZHAIBUQI.$loadingTips.text('正在加载...').show();
    },
    loadingHide : function(){
        "use strict";
        ZHAIBUQI.$loadingTips.hide();
    },
    moveAjax : function(){
        "use strict";
        ZHAIBUQI.$dustbin.empty();
        var $chosen = $(".active");
        if(!$(this).hasClass("passive") && $chosen.length > 0){
            ZHAIBUQI.tipsHtml("正在提交..");
            ZHAIBUQI.tipsShow();
            var send = [];
            $.each($chosen,function(){
                var mail = $(this).attr("value");
                send.push(mail);
            });
            $.ajax({type : "POST",
                dataType : "JSON",
                url : "json/event.php" ,
                data : send,
                success : function(data){
                    $chosen.appendTo(ZHAIBUQI.$dustbin);
                    var $a = $("<a>撤销</a>");
                    ZHAIBUQI.tipsHtml("提交已成功", $a);
                    ZHAIBUQI.tipsShow();
                },
                error : function(){
                    ZHAIBUQI.tipsHtml("加载失败，请重试");
                    ZHAIBUQI.tipsShow();
                }
            });
        }
    },
    removeAjax : function(){
        "use strict";
        ZHAIBUQI.$dustbin.empty();
        var $chosen = $(".active");
        if(!$(this).hasClass("passive") && $chosen.length > 0){
            ZHAIBUQI.tipsHtml("正在提交..");
            ZHAIBUQI.tipsShow();
            var send = [];
            $.each($chosen,function(){
                var mail = $(this).attr("value");
                send.push(mail);
            });
            $.ajax({type : "POST",
                dataType : "JSON",
                data : send,
                url : "json/event.php",
                success : function(data){
                    $chosen.appendTo(ZHAIBUQI.$dustbin);
                    var $a = $("<a>撤销</a>");
                    ZHAIBUQI.tipsHtml("提交已成功", $a);
                    ZHAIBUQI.tipsShow();
                },
                error : function(){
                    ZHAIBUQI.tipsHtml("提交失败，请重试");
                    ZHAIBUQI.tipsShow();
                }
            });
        }
    },
    groupAjax : function(){
        "use strict";
        ZHAIBUQI.$tips.hide();
        clearTimeout(ZHAIBUQI.clickTimer);
        var $liTarget = $(this);
        if(!ZHAIBUQI.click){
            ZHAIBUQI.clickTimer = setTimeout(function(){
                ZHAIBUQI.$dustbin.empty();
                if(!$liTarget.hasClass("passive")){
                    ZHAIBUQI.end = "";
                    ZHAIBUQI.click = true;
                    $(".passive").removeClass("passive");
                    $liTarget.addClass("passive");
                    $(".move ul").remove();
                    $(".move .group-selection").append($liTarget.parent().clone());
                    $(".move ul").slideUp(0);
                    ZHAIBUQI.loadingShow();
                    $.ajax({type : "POST",
                        dataType : "JSON",
                        url : "json/person.json",
                        success : function(data){
                            ZHAIBUQI.end = "";
                            ZHAIBUQI.userNum = 0;
                            ZHAIBUQI.$contacsBody.empty();
                            /* 添加人物ajax */
                            ZHAIBUQI.appendData(data);
                            ZHAIBUQI.click = false;
                        },
                        error : function(){
                            ZHAIBUQI.tipsHtml("加载失败，请重试");
                            ZHAIBUQI.tipsShow();
                            ZHAIBUQI.click = false;
                        }
                    });
                }
            },ZHAIBUQI.dbDelay);
        }
    },
    addAjax : function(){
        "use strict";
        var $chosen = $(".active");
        if($chosen.length > 0){
            ZHAIBUQI.tipsHtml("正在提交..");
            ZHAIBUQI.tipsShow();
            var send = [];
            $.each($chosen,function(){
                var mail = $(this).attr("value");
                send.push(mail);
            });
            $.ajax({type : "POST",
                dataType : "JSON",
                data : send,
                url : "json/event.php",
                success : function(data){
                    $chosen.removeClass("active");
                    ZHAIBUQI.tipsHtml("关注成功");
                    ZHAIBUQI.tipsHide();
                },
                error : function(){
                    ZHAIBUQI.tipsHtml("提交失败，请重试");
                    ZHAIBUQI.tipsShow();
                }
            });
        }
    },
    loadingAjax : function(){
        "use strict";
        ZHAIBUQI.end ="end";
        ZHAIBUQI.loading = true;
        $.ajax({
            type:"GET",
            dataType:"JSON",
            url: ZHAIBUQI.url + '&page=' + ZHAIBUQI.page ,
            success:ZHAIBUQI.appendData,
            error:function(){
                ZHAIBUQI.loading = false;
                ZHAIBUQI.end ="";
                ZHAIBUQI.tipsHtml("加载失败，请重试");
                ZHAIBUQI.tipsShow();
            }
        });
    },
    appendData : function(data){
        "use strict";
        ZHAIBUQI.page++;
        console.log(ZHAIBUQI.url + '&page=' + ZHAIBUQI.page);
        $.each(data.person, function(){
            var portrait = this.portrait,
                href = this.href,
                name = this.name,
                institude = this.institude,
                group = "组别:"+this.group,
                email = this.email,
                $img = $("<img />").attr({
                    "src":portrait,
                    "alt":name
                }),
            $portrait = $("<a></a>").addClass("pic").attr("href",href),
            $userInfo = $("<div></div>").addClass("user-info"),
            $group = $("<p></p>").addClass("extra").html(group),
            //$userDisplay = $("<div></div>").addClass("user-display").val(email);
            $userDisplay = $("<div></div>").addClass("user-display").val(email);
            $userInfo.append($("<a></a>").addClass("name").attr("href",href).html(name+"<br /><br />"));
            $userInfo.append($("<p></p>").html(institude));
            $userDisplay.append($portrait.append($img),$userInfo,ZHAIBUQI.$clearLeft.clone(),$group).insertBefore(ZHAIBUQI.$loadingTips);
            $userDisplay.fadeIn(100);
        });
        ZHAIBUQI.loading = false;
        ZHAIBUQI.$overflow.appendTo(ZHAIBUQI.$contacsBody);//清空contacts-body内所有元素的浮动
        ZHAIBUQI.loadingHide();
        ZHAIBUQI.end = data.end;
    },
    scrollAjax : function(){
        "use strict";
        var $target = $(this);
        var $doc = $(document);
        var bottom = 100;//表示滚到底部以上距离
        var wScrollTop = $target.scrollTop();
        var $toTop = $("#to-top");
        if(wScrollTop>100){
            $toTop.fadeIn(300);
        }
        else{
            $toTop.fadeOut(100);
        }
        if($doc.height()-$target.height()-wScrollTop<bottom){
            if(!ZHAIBUQI.loading){
                if(ZHAIBUQI.end!=="end"){
                    ZHAIBUQI.$loadingTips.text('正在加载...').show();
                    ZHAIBUQI.loadingAjax();
                }
                else{
                    ZHAIBUQI.$loadingTips.text('所有好友已加载完毕').show();
                }
            }
        }
    },
    newGroup : function(){
        "use strict";
        if($("#edit").length === 0){
            ZHAIBUQI.click = true;
            var $ulGroup = $(".group ul");
            var $edit = $("<input />").attr({
                "type":"text",
                "id":"edit"
            });
            var $li = $("<li></li>");
            $ulGroup.append($li.append($edit));
            $edit.blur(function(){
                var value = $(this).val();
                if(value !== ""){
                    $.ajax({
                        type:"POST",
                        dataType:"JSON",
                        url:"json/event.php",
                        success:function(){
                            $li.html(value);
                            $(".move ul").remove();
                            $(".move .group-selection").append($li.parent().clone());
                            $(".move ul").slideUp(0);
                            setTimeout(function(){
                                ZHAIBUQI.click = false;
                            },300);
                        },
                        error:function(){
                            ZHAIBUQI.tipsHtml("加载失败，请重试");
                            ZHAIBUQI.tipsShow();
                            setTimeout(function(){
                                ZHAIBUQI.click = false;
                            },300);
                        }
                    });
                }
                else{
                    $li.remove();
                }
                setTimeout(function(){
                    ZHAIBUQI.click = false;
                },300);
            });
            $edit.focus();
        }

    },
    rename : function(){
        "use strict";
        clearTimeout(ZHAIBUQI.clickTimer);
        if(!ZHAIBUQI.click){
            ZHAIBUQI.click = true;
            if($("#edit").length === 0){
                $(".delete").remove();
                var $target = $(this);
                var origin = $target.html();
                $target.empty().append(ZHAIBUQI.$edit.val(origin));
                $target.html("<input type='text' id='edit' value='"+origin+"' />");
                $("#edit").blur(function(){
                    var value = $(this).val();
                    if(value !== origin && value !== ""){
                        $.ajax({
                            type:"POST",
                            dataType:"JSON",
                            url:"json/event.php",
                            success:function(){
                                $target.html(value);
                                $(".move ul").remove();
                                $(".move .group-selection").append($target.parent().clone());
                                $(".move ul").slideUp(0);
                                setTimeout(function(){
                                    ZHAIBUQI.click = false;
                                },300);
                            },
                            error:function(){
                                ZHAIBUQI.tipsHtml("加载失败，请重试");
                                ZHAIBUQI.tipsShow();
                                setTimeout(function(){
                                    ZHAIBUQI.click = false;
                                },300);
                            }
                        });
                    }
                    else{
                        $target.html(origin);
                    }
                    setTimeout(function(){
                        ZHAIBUQI.click = false;
                    },300);
                });
                $("#edit").focus().hover();
            }
        }
    },
    searchShow : function(){
        "use strict";
        if(ZHAIBUQI.searching === ""){
            var text = $(this).val();
            ZHAIBUQI.searching = "searching";
            ZHAIBUQI.loadingShow();
            $.ajax({
                type:"GET",
                dataType:"JSON",
                data:text,
                url:"json/person.json",
                success:function(data){
                    ZHAIBUQI.$tips.hide();
                    ZHAIBUQI.searching = "";
                    ZHAIBUQI.$contacsBody.empty();
                    ZHAIBUQI.appendData(data);
                },
                error :function(){
                    ZHAIBUQI.searching = "";
                    ZHAIBUQI.$contacsBody.empty();
                    ZHAIBUQI.$contacsBody.html("加载出错，请重试");
                }
            });
        }
    },
    deleteGroup : function(){
        "use strict";
        var $li = $(this).parent();
        var groupName = $li.text();
        Apprise("确定要删除  <b>"+groupName+"</b>  分组吗？，<br />此分组下的人不会被取消关注。",{
            animation: 400,	// Animation speed
            buttons: {
                confirm: {
                    action: function() {
                        Apprise("close");
                        $(".group").mouseleave();
                        $.ajax({
                            type:"POST",
                            dataType:"JSON",
                            url:"json/event.php",
                            data:groupName,
                            success:function(){
                                var $ul = $li.parent();
                                $li.remove();
                                $(".move ul").remove();
                                $(".move .group-selection").append($ul.clone());
                                $(".move ul").slideUp(0);
                                ZHAIBUQI.tipsHtml("提交已成功");
                                ZHAIBUQI.tipsShow();
                                ZHAIBUQI.tipsHide();
                            },
                            error : function(){
                                ZHAIBUQI.tipsHtml("提交失败，请重试");
                                ZHAIBUQI.tipsShow();
                            }
                        });
                    }, // Callback function
                    className: null, // Custom class name(s)
                    id: 'confirm', // Element ID
                    text: '确定' // Button text
                },
                cancelable: {
                    action: function() { Apprise('close'); }, // Callback function
                    className: null, // Custom class name(s)
                    id: 'cancel', // Element ID
                    text: '取消' // Button text

                }
            },
            input: false, // input dialog
            override: true // Override browser navigation while Apprise is visible
        });
        return false;
    }

};
$(function(){
    "use strict";
    /* init */
    ZHAIBUQI.loadingHide();
    ZHAIBUQI.$tips.hide();
    var $toTop = $("#to-top");
    $toTop.hide();
    $toTop.on("click",function(){
        $("body,html").animate({scrollTop:0},500);
    });
    ZHAIBUQI.$loadingTips.appendTo(ZHAIBUQI.$contacsBody);

    /* 下拉菜单 */
    $(".group-selection").slideUp(1);
    var $operationBox = $("#operation-box");
    $operationBox.on("mouseenter",".operation",function(){
        $(this).find(".group-selection").stop(false,true).slideDown(200);
    });
    $operationBox.on("mouseleave",".operation",function(){
        $(this).find(".group-selection").stop(false,true).slideUp(200);
        $("#edit").blur();
    });

    /* 头像框选中及取消动画 */
    ZHAIBUQI.$contacsBody.on("click",".user-display",function(){
        $(this).toggleClass("active");
    });

    /* 撤退、重试事件 */
    ZHAIBUQI.$tips.on("click","a",function(){
        $.ajax({
            type : "POST",
            dataType : "JSON",
            url : "json/event.php",
            success : function(){
                $("#dustbin .active").prependTo(ZHAIBUQI.$contacsBody);
                $(".active").removeClass("active");
                ZHAIBUQI.tipsHtml("撤销成功");
                setTimeout(ZHAIBUQI.tipsHide,500);
            },
            error : function(){
                ZHAIBUQI.tipsHtml("撤销失败，请重试");
            }
        });
    });

    /* operation */
    var $moveTo = $(".move");
    $moveTo.on("click","li",ZHAIBUQI.moveAjax);

    var $remove = $(".remove");
    $remove.on("click",ZHAIBUQI.removeAjax);

    var $group = $(".group");
    $group.on("click","li",ZHAIBUQI.groupAjax);
    $group.on("dblclick","li",ZHAIBUQI.rename);
    $group.on("mouseenter","li",function(){
       ($("<b></b>").addClass("delete")).appendTo($(this));
    });
    $group.on("mouseleave","li",function(){
        $(".delete").remove();
    });
    $group.on("click",".delete",ZHAIBUQI.deleteGroup);

    var $add = $(".add");
    $add.on("click","li",ZHAIBUQI.addAjax);

    var $window = $(window);
    $window.on("scroll",ZHAIBUQI.scrollAjax);

    var $newGroup = $("#new-group");
    $newGroup.on("click",ZHAIBUQI.newGroup);

    /* search */
    var $searchInput = $("#friends-search");
    var searchText;
    $searchInput.on({
        "focus":function(){
            searchText = $searchInput.val();
        },
        "keyup":function(){
            if(searchText !== $searchInput.val()){
                ZHAIBUQI.searchShow.apply(this);
                searchText = $searchInput.val();
            }
        }
    });

});
