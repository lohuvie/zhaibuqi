$(document).ready(function() {
    id = $("#event-info").attr('activity');
    user_id = $("#event-info").attr('user');
    var like_num = 0;
    var join_num = 0;
    var is_love = -1;
    var is_join = -1;
    $.ajax({ 
        type:"GET", 
        url:"json/love_activity_count.php?activity="+id,
        datatype:"json",
        success:function(data){ 

            like_num = data.love_count;
            join_num = data.join_count;
            is_love = data.is_love;
            is_join = data.is_join;
            $('love-num').empty();
            $('#love-num').append(like_num);
            $('join-num').empty();
            $('#join-num').append(join_num);

            
            //判定是否喜欢了活动
            if(is_love == 0)
                $('#love').attr('class','collect-btn');
            else if(is_love == 1)
                $('#love').attr('class','');
            else
                console.log("服务器错误,请重新加载页面");
            
            //判定是否参与了活动
            if(is_join == 0)
                $('#join').attr('class','collect-btn');
            else if(is_join == 1)
                $('#join').attr('class','');
            else
                console.log("服务器错误,请重新加载页面");
            
        } 
    }); 
        /*
        var love = $(this); 
        var id = love.attr("rel"); //对应id 
        love.fadeOut(300); //渐隐效果 
        $.ajax({ 
            type:"GET", 
            url:"json/love_activity.php", 
            data:"activity="+id, 
            cache:false, //不缓存此页面 
            success:function(data){ 
                love.html(data); 
                love.fadeIn(300); //渐显效果 
            } 
        }); 
        return false; 
        */
    $("#love").click(function(){ 

        $.ajax({ 
            type:"POST",
            url:"php/love_activity.php", 
            datatype:"json",
            data:{love:is_love,activity:id},
            success:function(data){
                like_num = data.love_count;
                is_love = data.is_love;

                $('#love-num').empty();
                $('#love-num').append(like_num);

                //判定是否喜欢了活动
                if(is_love == 0)
                    $('#love').attr('class','collect-btn');
                else if(is_love == 1)
                    $('#love').attr('class','');
                else
                    console.log("服务器错误,请重新加载页面");

            } 
        });        
    }); 

    $("#join").click(function(){ 

        $.ajax({ 
            type:"POST",
            url:"php/join_activity.php", 
            datatype:"json",
            data:{join:is_join,activity:id},
            success:function(data){
                join_num = data.join_count;
                is_join = data.is_join;

                $('#join-num').empty();
                $('#join-num').append(join_num);


                //判定是否参与了活动
                if(is_join == 0)
                    $('#join').attr('class','collect-btn');
                else if(is_love == 1)
                    $('#join').attr('class','');
                else
                    console.log("服务器错误,请重新加载页面");

            } 
        });        
    }); 
}); 