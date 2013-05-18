<?php

require_once("util.php");
require_once("start-session.php");
if(isset($_SESSION['user_id'])){
    $dbc = mysqli_connect(host,user,password,database);
    $page_id =$_SESSION['comment_id'] ;//当前页面的id
    $user_id =   $_SESSION['user_id'];//当前用户的id
    $comment = $_POST['comment'];//评论内容
    $replyeeId = 0;
    if(isset($_POST['replyeeId'])){//是否是从属评论
        $replyeeId = $_POST['replyeeId'];
    }
    $time = date('Y-m-d H:i:s',time());//评论时间
    $userLink = 'http://'. $_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['PHP_SELF'])).'/personal-page.php?id='.$user_id;//用户的链接
    $replyeeLink =  'http://'. $_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['PHP_SELF'])).'/personal-page.php?id='.$replyeeId;//用户的链接

    $query = "select * from portrait where user_id ='$user_id'";
    $result = mysqli_query($dbc,$query);
    $row = mysqli_fetch_array($result);
    $portrait = $row['icon'];
    $portrait_path = PORTRAIT.$portrait;//图片路径

    $query = "insert into user_comment values(null,$user_id,$replyeeId,'$comment','$time',$page_id)";
    $result = mysqli_query($dbc,$query);
    $query = "select * from user where user_id ='$user_id'";
    $result = mysqli_query($dbc,$query);
    $userName="";
    //while(mysqli_fetch_array($result)){
        $row = mysqli_fetch_array($result);
        $userName = $row['nickname'];
    echo "{";
    echo "\"comment\":[";
    if($replyeeId !=0){
        $query = "select * from user where user_id ='$replyeeId'";
        $result = mysqli_query($dbc,$query);
        $row = mysqli_fetch_array($result);
        $replyee = $row['nickname'];//用户的从属用户名
    }

    if($replyeeId!=0){
        $comment1 = array ('userName'=>$userName,'userLink'=>$userLink,'userId'=>$user_id,'replyTime'=>$time,'replyeeLink'=>$replyeeLink
        ,'replyee'=>$replyee,'content'=>$comment,'userPhoto'=>$portrait_path);
    }else{
        $comment1 = array ('userName'=>$userName,'userLink'=>$userLink,'userId'=>$user_id,'replyTime'=>$time,
            'content'=>$comment,'userPhoto'=>$portrait_path);

    }

    echo json_encode($comment1);
    echo "]","}";
}else{
    header('HTTP/1.1 401 Unauthorized');
    header('status: 401 Unauthorized');
}


?>