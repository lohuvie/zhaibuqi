<?php
require_once('util.php');
require_once('start-session.php');
header('Content-type: text/json');
header('Content-type: application/json');
//未能传值
define('NO_VALUE',-3);

$space_time = $_POST['not_use_time'];
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
        if(isset($space_time)){
            //保存邮箱设置成功
            $query = "update user set space_time = '$space_time' where user_id =".$user_id."";
            $result = mysqli_query($dbc,$query);
        }else{
            //返回 未传值
        	$arr = array('space_time'=>NO_VALUE);
        }
        //返回 json数据
        $arr = array('space_time'=>1);
    }catch (Exception $e){
        //返回 数据库错误
        $arr = array('space_time'=>DATABASE_ERROR);
    }
}else{
    //返回 用户未登陆
    $arr = array('space_time'=>USER_NO_LOGIN);
}
echo json_encode($arr);
mysqli_close($dbc);

?>