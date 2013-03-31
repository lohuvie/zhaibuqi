<?php
//新建组
require_once("start-session.php");
require_once("util.php");

header('Content-type: text/json');
header('Content-type: application/json');
define('SUCCESS',0);
define('HAS_EXIST',1);



$group_name = $_POST['name'];
$user_id = USER_NO_LOGIN;
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}

$dbc = mysqli_connect(host,user,password,database);

//查询是否存在此组名
$query = "select * from user u
            join groups g on u.user_id=g.founder_id
            where name = $group_name";
$result = mysqli_query($dbc,$query);
if(mysqli_num_rows($result) == 0){
    //此组名不存在 可以新建
    try{
    $query = "insert into group(name,founder_id) values('$group_name',$user_id)";
    $result = mysqli_query($dbc,$query);
    $arr = array('msg'=>SUCCESS);
    }catch(Exception $e) {
        //数据库错误
        $arr = array('msg'=>DATABASE_ERROR);
    }
}else{
    //此组名存在 不允许新建
    $arr = array('msg'=>HAS_EXIST);
}

echo json_encode($arr);