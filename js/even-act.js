$(document).ready(function() {
    id = $("#event-info").attr('activity');
    user_id = $("#event-info").attr('user');
    var like_num = 0;
    var join_num = 0;
    var is_love = -1;
    var is_join = -1;

    var loveBtn = $('#love');    //love按钮
    var joinBtn = $('#join');    //join按钮
    var loveNum = $('#love-num');    //love数
    var joinNum = $('#join-num');    //join数

    var loveSpan = $("#love span");
    var joinSpan = $("#love span");

    var $alreadylove = $("<span>已喜欢!</span>");
    var $wantlove = $("<span>我也喜欢</span>")
    var $alreadyjoin = $("<span>已参与!</span>");
    var $wantjoin = $("<span>我要参与</span>")

    //加载的初始判断
    $.ajax({ 
        type:"GET", 
        url:"json/love_activity_count.php?activity="+id,
        datatype:"json",
        success:function(data){ 

            like_num = data.love_count;
            join_num = data.join_count;
            is_love = data.is_love;
            is_join = data.is_join;
            loveNum.empty();
            loveNum.append(like_num);
            joinNum.empty();
            joinNum.append(join_num);

            //判定是否喜欢了活动
            if(is_love === 0){
                loveBtn.attr('class','collect-btn');
                loveBtn.html($wantlove);
            }else if(is_love === 1){
                loveBtn.attr('class','already-btn');
                loveBtn.html($alreadylove);
            }else{
                console.log("服务器错误,请重新加载页面");
            }

            //判定是否参与了活动
            if(is_join === 0){
                joinBtn.attr('class','collect-btn');
                joinBtn.html($wantjoin);
            }
            else if(is_join === 1){
                joinBtn.attr('class','already-btn');
                joinBtn.html($alreadyjoin);
            }
            else{
                console.log("服务器错误,请重新加载页面");
            }
        } 
    }); 

    //点击“我也喜欢”按钮
    $("#love").click(function(){ 
        $.ajax({ 
            type:"POST",
            url:"php/love_activity.php", 
            datatype:"json",
            data:{love:is_love,activity:id},
            success:function(data){
                like_num = data.love_count;
                is_love = data.is_love;
                if(is_love === -1 && like_num === -1){
                    location.href = "login.php";
                }else if(is_love === -2 && like_num === -2){
                    console.log("服务器错误,请重新加载页面");
                }else{
                    loveNum.empty();
                    loveNum.html(like_num);

                    //判定是否喜欢了活动
                    if(is_love === 0){
                        loveBtn.attr('class','collect-btn');
                        loveBtn.html($wantlove);
                    }else if(is_love === 1){
                        loveBtn.attr('class','already-btn');
                        loveBtn.html($alreadylove);
                    }else{
                        console.log("服务器错误,请重新加载页面");
                    }
                }
            } 
        });        
    }); 

    //点击“我要参与”按钮
    $("#join").click(function(){ 
        $.ajax({ 
            type:"POST",
            url:"php/join_activity.php", 
            datatype:"json",
            data:{join:is_join,activity:id},
            success:function(data){
                join_num = data.join_count;
                is_join = data.is_join;
                if(is_join === -1 && join_num === -1){
                    location.href = "login.php";
                }else if(is_join === -2 && join_num === -2){
                    console.log("服务器错误,请重新加载页面");
                }else{
                    joinNum.empty();
                    joinNum.append(join_num);

                    //判定是否参与了活动
                    if(is_join === 0){
                        joinBtn.attr('class','collect-btn');
                        joinBtn.html($wantjoin);
                    }
                    else if(is_join === 1){
                        joinBtn.attr('class','already-btn');
                        joinBtn.html($alreadyjoin);
                    }
                    else{
                        console.log("服务器错误,请重新加载页面");
                    }
                }
            } 
        });        
    }); 
}); 