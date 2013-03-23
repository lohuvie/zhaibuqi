<?php
require_once("../php/start-session.php");
require_once("../php/util.php");

header('Content-type: text/json');
header('Content-type: application/json');
$activity_id = $_GET['activity'];
$user_id = $_SESSION['user_id'];
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = USER_NO_LOGIN;
}

$dbc = mysqli_connect(host,user,password,database);
//返回喜欢人数
$query = "select * from activity_love where activity_id = $activity_id";
$result = mysqli_query($dbc,$query);
$loveCount = mysqli_num_rows($result);
//返回参加人数
$query = "select * from activity_join where activity_id = $activity_id";
$result = mysqli_query($dbc,$query);
$joinCount = mysqli_num_rows($result);

//返回用户是否喜欢活动
$query = "select * from activity_love where activity_id = $activity_id and user_id = $user_id";
$result = mysqli_query($dbc,$query);
$isLove = mysqli_num_rows($result);

//返回用户是否参加活动
$query = "select * from activity_join where activity_id = $activity_id and user_id = $user_id";
$result = mysqli_query($dbc,$query);
$isJoin = mysqli_num_rows($result);

//返回json
$arr = array('love_count'=>$loveCount,'join_count'=>$joinCount,'is_love'=>$isLove,'is_join'=>$isJoin);
echo json_encode($arr);

mysqli_close($dbc);
?>