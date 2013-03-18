<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lohuvie
 * Date: 13-3-4
 * Time: 上午11:27
 * To change this template use File | Settings | File Templates.
 */
require_once("start-session.php");
require_once("util.php");
header('Content-type: text/json');
header('Content-type: application/json');

$joinActivity = $_POST['join'];
$activity_id = $_POST['activity'];
$user_id = $_SESSION['user_id'];
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = -1;
}

$dbc = mysqli_connect(host,user,password,database);
$query = "";
if($user_id != -1){
    if($joinActivity == 0){
        //喜欢该活动 向数据库添加该活动
        $query = "insert into activity_join values($activity_id,$user_id)";
        $result = mysqli_query($dbc,$query) or die("already join");

    }else{
        //不喜欢该活动 从数据库删除该活动
        $query = "delete from activity_join where activity_id = $activity_id and user_id = $user_id";
        $result = mysqli_query($dbc,$query) or die("already unjoin");
    }

    //返回喜欢人数
    $query = "select * from activity_join where activity_id = $activity_id";
    $result = mysqli_query($dbc,$query);
    $joinCount = mysqli_num_rows($result);

    //返回用户是否喜欢活动
    $query = "select * from activity_join where activity_id = $activity_id and user_id = $user_id";
    $result = mysqli_query($dbc,$query);
    $isJoin = mysqli_num_rows($result);
    //返回json数据
    $arr = array('join_count'=>$joinCount,'is_join'=>$isJoin);
    echo json_encode($arr);

    mysqli_close($dbc);
}else{
    //跳转至登陆页面

}
?>
