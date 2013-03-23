/* Add new feature 
    * User: lemonz
    * Date: 13-3-20
*/

var followBtn = $(".follow");	//关注按钮

var $alreadyfollow = $("<span>取消关注</span>");
var $following = $("<span>关注此人+</span>");


$(document).ready(function(){
	//点击关注按钮
	followBtn.click(function(){
        attention_status = followBtn.attr("value");
        id = $("#introduction").attr("value");
		$.ajax({ 
            type:"POST",
            url:"php/attention_user.php", 
            datatype:"json",
            data:{attention:attention_status,id:id},
            success:function(data){
            	
            	var status = data.attention_status;

            	if(status === -1){	    //用户未登录
            		location.href = "login.php"; 
            		console.log("用户未登录");
            	}else if(status === -2){     //数据库出错
            		console.log("数据库出错");
            	}else if(status === 0){      //用户之间无关系
            		followBtn.empty();
                    followBtn.append($following);
                    followBtn.attr('value',status);
            		console.log("用户之间无关系");
            	}else if(status === 1){      //已关注
            		followBtn.empty();
                    followBtn.append($alreadyfollow);
                    followBtn.attr('value',status);
            		console.log("已关注");
            	}else if(status === 2){      //互相关注
            	    followBtn.empty();
                    followBtn.append($alreadyfollow);
                    followBtn.attr('value',status);
                        console.log("互相关注");
            	}else if(status === 3){      //被关注
            		followBtn.empty();
                    followBtn.append($following);
                    followBtn.attr('value',status);
                        console.log("被关注");
            	}else{
            		console.log("其他");
            	}
            } 
        });     
	});
}); 