/* Created with JetBrains PhpStorm.
 * User: willkan
 * Date: 12-12-8
 * Time: 下午3:50
 * To change this template use File | Settings | File Templates.
 */
var end;
var type = 'all';
var pageCount = 0;
var loadMore = function(){
    "use strict";
    $.ajax({
        url:"json/waterfall.php?type=" + type + '&page=' + pageCount,
        dataType:"json",
        type:"GET",
        success:function(data){
            pageCount++;
            var $waterfall = $("#waterfall");
            function add(){
                var href = this.href;
                var picPath = this.picPath;
                var title = this.title;
                var time = "时间:"+this.time;
                var place = "地点:"+this.place;
                var $singleActivity = $("<div></div>").addClass("single-activity");
                var $title = $("<a></a>").addClass("activity-title").attr("href",href).html(title);
                var $pic = $("<a></a>").addClass("activity-pic").html("<img src='"+picPath+"' alt='"+title+"' />").attr("href",href);
                var $time = $("<p></p>").html(time);
                var $place = $("<p></p>").html(place);
                console.log($singleActivity,$title,$pic,$time,$place);
                $singleActivity.append($pic).append($title).append($time).append($place).hide().appendTo($waterfall);
                $waterfall.masonry('appended',$singleActivity,true);
                $singleActivity.fadeIn(100);
            }
            /*处理json数据，添加到html中*/
            $.each(data.waterfall, add);
            end = data.end;
            $("#add-tips").fadeOut(300);
        },
        error:function(){
            $("#add-tips").html("加载出错，请刷新网页重试");
        }
    });
};
$(function(){
    "use strict";
    /* waterfall */
    var $waterfall = $('#waterfall');
    $waterfall.imagesLoaded( function(){
        $waterfall.masonry({
            itemSelector : '.single-activity',
            columnWidth: 245
        });
    });
    /* back to top */
    var $toTop = $("#to-top");
    $toTop.hide();
    $toTop.on("click",function(){
        $("body,html").animate({scrollTop:0},500);
    });

    /*init*/
    loadMore();

    /* rechabottom */
    var $addTips = $("#add-tips");
    $addTips.hide();
    var $window = $(window);
    $window.on("scroll",function(){
        var $target = $(this);
        var $doc = $(document);
        var bottom = 100;//表示滚到底部以上距离
        var wScrollTop = $target.scrollTop();
        if(wScrollTop>100){
            $toTop.fadeIn(300);
        }
        else{
            $toTop.fadeOut(100);
        }
        if($doc.height()-$target.height()-wScrollTop<bottom){
            if(end!=="end"){
                $addTips.fadeIn(300);
                loadMore();
            }
            else{
                $addTips.html("所有活动已加载完").fadeIn(300);
            }
        }
    });

});
