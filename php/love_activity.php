<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lohuvie
 * Date: 13-3-4
 * Time: 上午10:46
 * To change this template use File | Settings | File Templates.
 */
require_once("start-session.php");
require_once("util.php");
header('Content-type: text/json');
header('Content-type: application/json');

$isLoveActivity = $_POST['love'];
$activity_id = $_POST['activity'];
$user_id = $_SESSION['user_id'];
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = USER_NO_LOGIN;
}

$dbc = mysqli_connect(host,user,password,database);
$query = "";
if($user_id != USER_NO_LOGIN){
    try{
        if($isLoveActivity == 0){
            //喜欢该活动 向数据库添加该活动
            $query = "insert into activity_love values($activity_id,$user_id)";
            $result = mysqli_query($dbc,$query);
            $isLoveActivity = 1;
        }else{
            //不喜欢该活动 从数据库删除该活动
            $query = "delete from activity_love where activity_id = $activity_id and user_id = $user_id";
            $result = mysqli_query($dbc,$query);
            $isLoveActivity = 0;
        }

        //返回喜欢人数
        $query = "select * from activity_love where activity_id = $activity_id";
        $result = mysqli_query($dbc,$query);
        $loveCount = mysqli_num_rows($result);

        //返回json数据
        $arr = array('love_count'=>$loveCount,'is_love'=>$isLoveActivity);
    }catch (Exception $e){
        //返回数据库错误
        $arr = array('love_count'=>DATABASE_ERROR,'is_love'=>DATABASE_ERROR);
    }
}else{
    //返回用户未登陆
    $arr = array('love_count'=>USER_NO_LOGIN,'is_love'=>USER_NO_LOGIN);
}

echo json_encode($arr);
mysqli_close($dbc);