/* Created with JetBrains PhpStorm.
 * User: willkan
 * Date: 12-12-8
 * Time: 下午3:50
 * To change this template use File | Settings | File Templates.
 */
(function(){
    "use strict";
    if(!window.ZHAIBUQI){
        window.ZHAIBUQI = {};
    }
    ZHAIBUQI.index = {
        end:'',
        type:'all',
        pageCount:0
    };
    ZHAIBUQI.index.loadMore = function(){
        $.ajax({
            url:"json/waterfall.php?type=" + ZHAIBUQI.index.type + '&page=' + ZHAIBUQI.index.pageCount,
            dataType:"json",
            type:"GET",
            success:function(data){
                ZHAIBUQI.index.pageCount++;
                var $waterfall = $("#waterfall");
                function add(){
                    var href = this.href,
                        picPath = this.picPath,
                        title = this.title,
                        time = "时间:"+this.time,
                        place = "地点:"+this.place,
                        $singleActivity = $("<div></div>").addClass("single-activity"),
                        $title = $("<a></a>").addClass("activity-title").attr("href",href).html(title),
                        $img = $('<img/>').attr({
                            src:picPath,
                            alt:title
                        }),
                        $pic = $("<a></a>").addClass("activity-pic").attr('href',href).append($img),
                            //html("<img src='"+picPath+"' alt='"+title+"' />").attr("href",href),
                        $time = $("<p></p>").html(time),
                        $place = $("<p></p>").html(place);
                    console.log($singleActivity,$title,$pic,$time,$place);
                    $img.on('load',function(){
                        if($img.get(0).complete && $img.get(0).width !== 0){
                            $singleActivity.append($pic,$title,$time,$place).hide().appendTo($waterfall);
                            $waterfall.masonry('appended',$singleActivity,true);
                            $singleActivity.fadeIn(100);
                        }
                    });
                }
                /*处理json数据，添加到html中*/
                $.each(data.waterfall, add);
                ZHAIBUQI.index.end = data.end;
                $("#add-tips").fadeOut(300);
            },
            error:function(){
                $("#add-tips").html("加载出错，请刷新网页重试");
            }
        });
    };
})();
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
    ZHAIBUQI.index.loadMore();

    /* reach bottom */
    var $addTips = $("#add-tips");
    //$addTips.hide();
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
            if(ZHAIBUQI.index.end!=="end"){
                $addTips.fadeIn(300);
                ZHAIBUQI.index.loadMore();
            }
            else{
                $addTips.html("所有活动已加载完").fadeIn(300);
            }
        }
    });

});
