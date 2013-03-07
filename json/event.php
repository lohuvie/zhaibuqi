<?php
require_once "../php/start-session.php";
require_once("../php/util.php");

header('Content-type: text/json');
header('Content-type: application/json');

$page = $_GET['page'];
$type = $_GET['type'];
$user_id = $_GET['id'];

$typeStr = "";
$typeStr2 = "";
//判定类型
switch($type){
    case 'like':
        $typeStr = "join activity_love on a.activity_id = activity_love.activity_id";
        $typeStr2 = "activity_love";
        break;
    case 'join':
        $typeStr = "join activity_join on a.activity_id = activity_join.activity_id";
        $typeStr2 = "activity_join";
        break;
    case 'host':
        $typeStr = "";
        $typeStr2 = "a";
        break;
}
//判断页数
$page *= 4;
$dbc = mysqli_connect(host,user,password,database);
$query = "select * from activity a
join activity_photo on a.activity_id = activity_photo.activity_id ".$typeStr.
" where ".$typeStr2.".user_id = $user_id and a.approved = 1 limit ".$page.",4";
$data = mysqli_query($dbc,$query);
$echoStr = "";
$i = 0;
$href = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/activity.php?activity=';
//json
echo "{";
echo "\"activity\":[";
while($result = mysqli_fetch_array($data,MYSQLI_ASSOC)){
    $echoStr .= "{\"picSrc\":\"".UPLOAD_PATH_FRONT_TO_BACK.$result['photo']."\",";
    $echoStr .= "\"alt\":\"".$result['name']."\",";
    $echoStr .= "\"title\":\"".$result['name']."\",";
    $echoStr .= "\"href\":\"".$href.$result['activity_id']."\"},";
    $i++;
}
$echoStr = substr($echoStr,0,strlen($echoStr)-1);//删除最后一个逗号
echo $echoStr;
echo "],";
echo "\"achieveEnd\":\"".(($i < 4)?"end":"")."\"";
echo "}";

mysqli_close($dbc);
/*
{
    "activity":[
        {"a":"a"},
        {"a":"a"},
        {"a":"a"},
        {"a":"a"}
    ],
    "achieveEnd":""
}*/
?>
