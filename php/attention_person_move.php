<?php
//新建组
require_once("start-session.php");
require_once("util.php");

header('Content-type: text/json');
header('Content-type: application/json');
define('SUCCESS',1);
define('GROUP_NOT_EXIST',0);


$persons = $_POST['person'];
$group_id = $_POST['groupId'];
$person_query = "";
$obj = json_decode($persons);

for($i=0;$i<count($obj);$i++){
    $person_query .= ' attention_groups_id ='.$obj[$i]->{'groupValue'}.' or';
}
unset($i);

$person_query = substr($person_query,0,-2);
unset($person);
$user_id = USER_NO_LOGIN;
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}

$dbc = mysqli_connect(host,user,password,database);

try{
    $query = "delete from attention_groups where $person_query";
    $result = mysqli_query($dbc,$query);
    for($i=0;$i<count($obj);$i++){
        if($group_id !=0){
            $query = "insert into attention_groups(attention_fan_id,groups_id) values (".$obj[$i]->{'personValue'}.",".$group_id.")";
            $result = mysqli_query($dbc,$query);
        }
    }
    $arr = array('msg'=>SUCCESS);
}catch(Exception $e) {
    //数据库错误
    $arr = array('msg'=>DATABASE_ERROR);
}


echo json_encode($arr);
mysqli_close($dbc);