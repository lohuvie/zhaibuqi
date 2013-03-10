<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lohuvie
 * Date: 13-3-4
 * Time: 上午10:46
 * To change this template use File | Settings | File Templates.
 */
require_once "start-session.php";
require_once("util.php");

$loveActivity = $_GET['love'];
$activity_id = $_GET['activity'];
$user_id = $_SESSION['user_id'];
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = -1;
}

$dbc = mysqli_connect(host,user,password,database);
$query = "";
if(user_id != -1){
	if($loveActivity == 0){
	    //喜欢该活动 向数据库添加该活动
	    $query = "insert into activity_love values($activity_id,$user_id)";
	    $result = mysqli_query($dbc,$query) or die("already love");

	}else{
	    //不喜欢该活动 从数据库删除该活动
	    $query = "delete from activity_love where activity_id = $activity_id and user_id = $user_id";
	    $result = mysqli_query($dbc,$query) or die("already unlove");
	}

	//返回喜欢人数
	$query = "select * from activity_love where activity_id = $activity_id";
	$result = mysqli_query($dbc,$query);
	$rowCount = mysqli_num_rows($result);

	//返回用户是否喜欢活动
	$query = "select * from activity_love where activity_id = $activity_id and user_id = $user_id";
	$result = mysqli_query($dbc,$query);
	$isLove = mysqli_num_rows($result);
	//返回json数据
	$arr = array('love_count'=>$loveCount,'is_love'=>$isLove);
	echo json_encode($arr);

	mysqli_close($dbc);
}else{
	//跳转至登陆页面
}
?>
