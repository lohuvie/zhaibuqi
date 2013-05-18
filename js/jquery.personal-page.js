/* Created with JetBrains PhpStorm.
    * User: willkan
    * Date: 12-12-1
    * Time: 下午3:50
    * To change this template use File | Settings | File Templates.
*/
var introId = $('#introduction').attr('value');
var pageCount = {
    like:0,
    join:0,
    host:0
};
var pageWidth = 620;
var next = "-="+pageWidth;
var pre = "+="+pageWidth;
var state = {
    /* end用来判断是否无新数据，若无心数据则按键变灰禁用 */
    end:{
        "like":"",
        "join":"",
        "host":""
    },
    /* loading用来判断动画是否加载中，若加载中则按键无动作 */
    loading:{
        "like":"",
        "join":"",
        "host":""
    }
};

var ajaxLoading = function($btn,show){
    "use strict";
    var $loading = $btn.parent("h3").find(".loading");
    if(show === "loading"){
        $loading.css("visibility","visible");
    }
    else{
        $loading.css("visibility","hidden");
    }
};

var btnDisable = function($btn){
    "use strict";
    $btn.css({
        "color": "#DDD",
        "cursor": "default"
    });
};

var btnEnable = function($btn){
    "use strict";
    $btn.css({
        "color": "black",
        "cursor": "pointer"
    });
};
var slide = function($object,slideWidth,end){
    "use strict";
    var eventParentID = $object.parents(".event").attr("id");
    state.loading[eventParentID] = "loading";
    $object.stop().clearQueue().animate({
            marginLeft:slideWidth
        },300,function(){
        /*激活/禁用 上一页*/
        var $preBtn = $("#"+eventParentID+" .pre-btn");
        var $nextBtn = $("#"+eventParentID+" .next-btn");
        if(parseInt($object.css("marginLeft"),10)>=0){
            btnDisable($preBtn);
        }
        else{
            btnEnable($preBtn);
        }
        if(end === "end"){
            btnDisable($nextBtn);
        }
        if(slideWidth === pre){
            btnEnable($nextBtn);
            pageCount[eventParentID] -= 1;
        } else{
            pageCount[eventParentID] += 1;
        }
        state.loading[eventParentID] = "";
    });
};
var activityAppend = function(data,eventParentID){
    "use strict";
    var $ul = $("#"+eventParentID+" ul");
    /*处理json数据，添加到html中*/
    var end = data.achieveEnd;
    if(end === "end"){
        state.end[eventParentID] = "end";
    } else{
        var $nextBtn = $("#"+eventParentID+" .next-btn");
        btnEnable($nextBtn);
    }
    $.each(data.activity, function(){
        var $singleActivity = $("<li></li>");
        var picSrc = this.picSrc;
        var alt = this.alt;
        var title = this.title;
        var href = this.href;
        var $pic = $('<div/>').addClass('pic-link-box').append(
            $("<a></a>").addClass('pic-link').attr('href',href).html("<img src='"+picSrc+"' alt='"+alt+"' />"));
        var $title = $("<h5></h5>").html("<a href='" + href + "'>"+title+"</a>");
        $singleActivity.append($pic,$title).addClass("single-activity").appendTo($ul);
    });
    ajaxLoading($("#"+eventParentID+" .loading"), "");
    /*滑动*/
    if($ul.find("li").length>4){
        slide($ul,next,end);
    }
};
var nextPage = function(event){
    "use strict";
    var $event = $(event.target);
    var eventParentID = $event.parents(".event").attr("id");
    if(state.loading[eventParentID] !== "loading"){
        var $ul = $event.parents(".event").find("ul");
        var $activities = $ul.find(".single-activity");
        var currentPage = -parseInt($ul.css("marginLeft"),10)/pageWidth;
        var cachePage = Math.floor(($activities.length-1)/4);
        var url = "json/event.php?page=" + (pageCount[eventParentID] + 1) +
            "&type=" + eventParentID + "&id=" + introId;
        console.log(url);
        if(currentPage===cachePage){
            if(state.end[eventParentID] !== "end"){
                ajaxLoading($event, "loading");
                $.ajax({type:"GET",
                    dataType:"JSON",
                    url:url,
                    success:function(data){
                        activityAppend(data,eventParentID);
                    }
                });
            }
        }
        else if(currentPage<cachePage){
            var end;
            if(currentPage+1 === cachePage && state.end[eventParentID] === "end"){
                end = "end";
            }
            slide($ul,next,end);
        }
    }
};

var prePage = function(event){
    "use strict";
    var $event = $(event.target);
    var eventParentID = $event.parents(".event").attr("id");
    if(state.loading[eventParentID] !== "loading"){
        var $ul = $event.parents(".event").find("ul");
        if(parseInt($ul.css("marginLeft"),10)<0){
            slide($ul,pre);
        }
    }
};

$(document).ready(function(){
    "use strict";
    var $event = $("div.event");
    var $ul = $("div.event ul");
    var $nextBtn = $event.find(".next-btn");
    var $preBtn = $event.find(".pre-btn");
    /* 初始化加载图案 */
    var $parent = $event.find("h3");
    var pic = $("<span>加载中</span>").addClass("loading").css({
        "position":"absolute",
        "right":0,
        "font-size":11,
        "color":"#84C43C",
        "visibility":"hidden"
    });
    var $emptyTips = $('<div/>').addClass('empty-tips');
    pic.appendTo($parent);
    /* 初始化按钮事件 */
    //btnEnable($nextBtn);
    $preBtn.on("click", prePage );
    $nextBtn.on("click", nextPage );
    /* 加载初始4个活动 */
    $.each($ul,function(){
            var eventParent = $(this).parents(".event"),
                eventParentID = eventParent.attr("id");
            var url = "json/event.php?page=0"  +
                "&type=" +eventParentID + "&id=" + introId;
            ajaxLoading($("#"+eventParentID+" .loading"), "loading");
            console.log(url);
            $.ajax({type:"GET",
                dataType:"JSON",
                url:url,
                success:function(data){
                    activityAppend(data,eventParentID);
                    if(eventParent.find('.single-activity').length === 0){
                        switch (eventParentID){
                            case 'join':
                                $emptyTips.text('暂时还没有参加的活动喔');
                                break;
                            case 'like':
                                $emptyTips.text('暂时还没有喜欢的活动喔');
                                break;
                            case 'host':
                                $emptyTips.text('暂时还没有发布的活动喔');
                                break;
                            default:
                                break;
                        }
                        $emptyTips.clone().appendTo(eventParent);
                    }
                }
            });
    }
    );
});

