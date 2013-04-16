<?php
//新建组
require_once("start-session.php");
require_once("util.php");

header('Content-type: text/json');
header('Content-type: application/json');
define('SUCCESS',0);
define('GROUP_NO_EXIST',1);


$group_id = $_POST['groupId'];
$group_name = $_POST['groupName'];
$user_id = USER_NO_LOGIN;
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}

$dbc = mysqli_connect(host,user,password,database);

//查询是否存在此组名
$query = "select * from  groups g  where g.groups_id = $group_id";
$result = mysqli_query($dbc,$query);
if(mysqli_num_rows($result) != 0){
    //此组名不存在 可以重命名
    try{
        $query = "update groups set name='$group_name' where groups_id = $group_id";
        $result = mysqli_query($dbc,$query);
        $arr = array('msg'=>SUCCESS);
    }catch(Exception $e) {
        //数据库错误
        $arr = array('msg'=>DATABASE_ERROR);
    }
}else{
    //此组名存在 不允许新建
    $arr = array('msg'=>GROUP_NO_EXIST);
}

echo json_encode($arr);
mysqli_close($dbc);