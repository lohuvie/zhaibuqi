<?php
require_once("../php/util.php");

header('Content-type: text/json');
header('Content-type: application/json');

$page = $_GET['page'];
$type = $_GET['type'];

$typeStr = "";
$typeStr2 = "";
//判定类型
if($type == "all"){
    $type = "";
}else{
    $type = "and type ='".$type."' ";
}
//判断页数
$page *= 6;
$dbc = mysqli_connect(host,user,password,database);
$query = "select * from activity a
join activity_photo aphoto on a.activity_id = aphoto.activity_id
join activity_time atime on a.activity_id = atime.activity_id
where aphoto.iscover = 1 and a.approved = 1 $type limit ".$page.",6";
$data = mysqli_query($dbc,$query);
$echoStr = "";
$i = 0;
//json
echo "{";
echo "\"waterfall\":[";
while($result = mysqli_fetch_array($data,MYSQLI_ASSOC)){
    $echoStr .= "{\"picPath\":\"".UPLOAD_PATH_FRONT_TO_BACK.$result['photo']."\",";
    $echoStr .= "\"href\":\"activity.php?activity=".$result['activity_id']."\",";
    $echoStr .= "\"title\":\"".$result['name']."\",";
    //查询活动时间
    $date = $result['date'];
    $time_begin = $result['time_begin'];
    $time_end = $result['time_end'];
    //查询活动类型
    $activity_type = $result['type'];
    switch($activity_type){
        case 'club':
            $activity_type = "社团活动";
            break;
        case 'match':
            $activity_type = "比赛";
            break;
        case 'play':
            $activity_type = "出去耍";
            break;
        case 'lecture':
            $activity_type = "讲座";
            break;
        default:
            $activity_type = "活动";
            break;
    }

    //计算星期几 月 日
    $month = date("m",strtotime($date));
    $day = date("d",strtotime($date));
    switch(date("w",strtotime($date))){
        case 0: $week = '周日';
            break;
        case 1: $week = '周一';
            break;
        case 2: $week = '周二';
            break;
        case 3: $week = '周三';
            break;
        case 4: $week = '周四';
            break;
        case 5: $week = '周五';
            break;
        case 6: $week = '周六';
            break;
    }
    $time = $month.'月'.$day.'日 '.$week.' '.date("H:i",strtotime($time_begin))." - ".date("H:i",strtotime($time_end));
    $echoStr .= "\"type\":\"".$activity_type."\",";
    $echoStr .= "\"time\":\"".$time."\",";
    $echoStr .= "\"place\":\"".$result['site']."\"},";
    $i++;
}
$echoStr = substr($echoStr,0,strlen($echoStr)-1);//删除最后一个逗号
echo $echoStr;
echo "],";
echo "\"end\":\"".(($i < 4)?"end":"")."\"";
echo "}";

mysqli_close($dbc);

/*
{
    "waterfall":[
        {
            "picPath":"images/test.png",
            "href":"a",
            "title":"title",
            "time":"a",
            "place":"a",
            "intro":"a"
        },
        {
            "picPath":"images/test.png",
            "href":"a",
            "title":"title",
            "time":"a",
            "place":"a",
            "intro":"a"
        },
        {
            "picPath":"images/test.png",
            "href":"a",
            "title":"title",
            "time":"a",
            "place":"a",
            "intro":"a"
        },
        {
            "picPath":"images/test.png",
            "href":"a",
            "title":"title",
            "time":"a",
            "place":"a",
            "intro":"a"
        }
    ],
    "end":"end"
}
*/
?>
