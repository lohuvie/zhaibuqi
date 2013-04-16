<?php
//删除组
require_once("start-session.php");
require_once("util.php");

header('Content-type: text/json');
header('Content-type: application/json');
define('SUCCESS',0);


$group_id = $_POST['groupId'];
$user_id = USER_NO_LOGIN;
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}

$dbc = mysqli_connect(host,user,password,database);

    //此组名不存在 可以重命名
    try{
        $query = "delete from attention_groups where groups_id = $group_id";
        $result = mysqli_query($dbc,$query);
        $query = "delete from groups where groups_id = $group_id";
        $result = mysqli_query($dbc,$query);
        $arr = array('msg'=>SUCCESS);
    }catch(Exception $e) {
        //数据库错误
        $arr = array('msg'=>DATABASE_ERROR);
    }
echo json_encode($arr);
mysqli_close($dbc);