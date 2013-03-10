<?php
require_once("../php/util.php");
header('Content-type: text/json');
header('Content-type: application/json');
$activity_id = $_GET['activity'];

$dbc = mysqli_connect(host,user,password,database);
//返回喜欢人数
$query = "select * from activity_join where activity_id = $activity_id";
$result = mysqli_query($dbc,$query);
$rowCount = mysqli_num_rows($result);

//返回json
$arr = array('join_count'=>$rowCount);
echo json_encode($arr);

mysqli_close($dbc);
?>