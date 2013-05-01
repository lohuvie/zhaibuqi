<?php
require_once"util.php";
require_once"start-session.php";
$dbc = mysqli_connect(host,user,password,database);
$page_id = $_GET['id'];//当前页面的id
$user_id =   $_SESSION['user_id'];//当前用户的id
$comment = $_POST['comment'];//评论内容
$replyeeId = -1;
if(isset($_POST['replyeeId'])){//是否是从属评论
    $replyeeId = $_POST['replyeeId'];
}
$time = date('Y-m-d H:i:s',time());//评论时间
$userLink = 'http://'. $_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['PHP_SELF'])).'/personal-page.php?id='.$user_id;//用户的链接

$query = "insert into user_comment value(null,$user_id,$replyeeId,$comment,$time,$page_id)";
$result = mysqli_query($dbc,$query);
$querry = "select * from user where user_id ='$user_id'";
$result = mysqli_query($dbc,$query);
while(mysqli_num_rows($result)){
    $row = mysql_fetch_array($result);
    $userName = $row['nickname'];
}
if(empty($replyId)){
    $arr = array ('userName'=>$userName,'userLink'=>$userLink,'userId'=>$user_id,'replyTime'=>$time,'replyeeLink'=>5
    ,'replyeeId'=>5,'content'=>5,'userPhoto'=>5);//返回json数据
}

echo json_encode($arr);

//"comment":[
//        {
//            "userName":"abc",
//            "userLink":"#",
//            "userId":"",
//            "replyTime":"10月13日 18:40",
//            "replyee":"cba",
//            "replyeeLink":"#",
//            "replyeeId":"",
//            "content":"fuck",
//            "userPhoto":""
//        }
//    ]




?>