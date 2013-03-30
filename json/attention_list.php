<?php
require_once("../php/start-session.php");
require_once("../php/util.php");

header('Content-type: text/json');
header('Content-type: application/json');
$personal_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = USER_NO_LOGIN;
}

$dbc = mysqli_connect(host,user,password,database);



//返回json
$arr = array('love_count'=>$loveCount,'join_count'=>$joinCount,'is_love'=>$isLove,'is_join'=>$isJoin);
echo json_encode($arr);

mysqli_close($dbc);
/*
 * {
    "person":[
        {
            "portrait":"images/search-head.jpg",
            "href":"a",
            "name":"name",
            "institude":"institude",
            "group":"group",
            "email":"email"
        },
        {
            "portrait":"images/search-head.jpg",
            "href":"a",
            "name":"name",
            "institude":"institude",
            "group":"group",
            "email":"email"
        },
        {
            "portrait":"images/search-head.jpg",
            "href":"a",
            "name":"name",
            "institude":"institude",
            "group":"group",
            "email":"email"
        },
        {
            "portrait":"images/search-head.jpg",
            "href":"a",
            "name":"name",
            "institude":"institude",
            "group":"group",
            "email":"email"
        }
    ],
    "end":"end"
}

 */
?>

