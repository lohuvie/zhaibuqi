<?php
require_once("php/util.php");
require_once("php/start-session.php");
function curPageSelfUrl()
{
    $php_self = $_GET['pageType'];
    if($php_self=='a'){//获取第一个匹配的位置或者返回false
        $php_self="activity_comment.php";
    }else{
        $php_self="user_comment.php";
    }

    return $php_self;
}
$url = curPageSelfUrl();
$page_id = $_GET['pageId'];//当前页面的id
$dbc = mysqli_connect(host,user,password,database);
if($url=="user_comment.php"){
    $query = "select * from user_comment x  where  id<(select MAX(id) from user_comment y where home_user_id ='$page_id' )
     and id>(select MIN(id) from user_comment z where home_user_id ='$page_id' )";//按时间排序
    }else{
    $query = "select * from activity_comment x  where  id<(select MAX(id) from activity_comment y where activity_id ='$page_id' )
    and id>(select MIN(id) from activity_comment z where activity_id ='$page_id' )";//按时间排序

}
$result1 = mysqli_query($dbc,$query);
echo "{";
echo "\"comment\":[";
$num = mysqli_num_rows($result1);
$count =1;
while($row1 = mysqli_fetch_array($result1)){
    $user_id = $row1['user_id'];//当前用户的id

    $comment = $row1['comment'];//评论内容
if($url=="user_comment.php"){
    $time = $row1['time'];//评论时间
}else{
    $time = $row1['publish_time'];
}
    $replyeeId= $row1['parent_id'];
    $query = "select * from portrait where user_id ='$user_id'";
    $result = mysqli_query($dbc,$query);
    $row = mysqli_fetch_array($result);
    $portrait = $row['icon'];
    $portrait_path = PORTRAIT.$portrait;//图片路径 $count ++;

//$query = "insert into user_comment values(null,$user_id,$replyeeId,$comment,'$time',$page_id)";
//$result = mysqli_query($dbc,$query);
    $query = "select * from user where user_id ='$user_id'";
    $result = mysqli_query($dbc,$query);

//while(mysqli_fetch_array($result)){
    $row2 = mysqli_fetch_array($result);
    $userName = $row2['nickname'];

    $userLink = 'http://'. $_SERVER['HTTP_HOST'].(dirname($_SERVER['PHP_SELF'])).'/personal-page.php?id='.$user_id;//用户的链接
    $replyeeLink =  'http://'. $_SERVER['HTTP_HOST'].(dirname($_SERVER['PHP_SELF'])).'/personal-page.php?id='.$replyeeId;//用户的链接
    if($replyeeId !=0){
        $query = "select * from user where user_id ='$replyeeId'";
        $result = mysqli_query($dbc,$query);
        $row = mysqli_fetch_array($result);
        $replyee = $row['nickname'];//用户的从属用户名
    }


    $comment1="";
    if($replyeeId!=0){
    $comment1 = array ('userName'=>$userName,'userLink'=>$userLink,'userId'=>$user_id,'replyTime'=>$time,'replyeeLink'=>$replyeeLink
    ,'replyee'=>$replyee,'content'=>$comment,'userPhoto'=>$portrait_path);
    }else{
        $comment1 = array ('userName'=>$userName,'userLink'=>$userLink,'userId'=>$user_id,'replyTime'=>$time,
            'content'=>$comment,'userPhoto'=>$portrait_path);

    }



    echo json_encode($comment1);
        if(($count)<$num){
        echo ",";
        }
        $count++;
}

    echo "]","}";

?>
