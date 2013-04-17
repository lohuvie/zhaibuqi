<?php
//新建组
require_once("start-session.php");
require_once("util.php");

header('Content-type: text/json');
header('Content-type: application/json');
define('SUCCESS',1);
define('GROUP_NOT_EXIST',0);


$persons = $_POST['person'];
$groups = $_POST['group'];
$group_id = $_POST['groupId'];
$person_array = explode(' ',$persons);
$group_array = explode(' ',$groups);
$person_query = "";

for($i=0;i<count($person_array);$i++){
    $person_query .= ' (attention_fan_id ='.$person_array[$i] .'and groups_id ='.$$group_array[$i].') or';
}
$person_query = substr($person_query,0,-2);
unset($person);
$user_id = USER_NO_LOGIN;
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}

$dbc = mysqli_connect(host,user,password,database);

//查询该组是否存在
$query = "select * from groups g
            where g.groups_id = $group_id";
$result = mysqli_query($dbc,$query);
if(mysqli_num_rows($result) != 0){
    //此组名存在 可以移动
    try{
        $query = "update attention_groups set groups_id = $group_id where $person_query";
        $result = mysqli_query($dbc,$query);
        $arr = array('msg'=>SUCCESS);
    }catch(Exception $e) {
        //数据库错误
        $arr = array('msg'=>DATABASE_ERROR);
    }
}else{
    //此组名不存在 不允许移动
    $arr = array('msg'=>GROUP_NOT_EXIST);
}

echo json_encode($arr);
mysqli_close($dbc);