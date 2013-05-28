<?php
require_once"util.php";
/**
 * Created by JetBrains PhpStorm.
 * User: lkjxa186
 * Date: 13-5-28
 * Time: 下午8:11
 * To change this template use File | Settings | File Templates.
 */


$email = $_GET['email'];
$nickname = $_GET['nickname'];
$user_id = $_GET['user_id'];
$dbc = mysqli_connect(host,user,password,database);


$query = "select activity_id from activity where user_id =$user_id";
$result = mysqli_query($dbc, $query);
while($row = mysqli_fetch_array($result)){
    $id = $row['activity_id'];
    //从activity_photo中删除
    $query = "DELETE FROM activity_photo WHERE activity_id = $id LIMIT 1";
    mysqli_query($dbc, $query)or die(错误1);
    //从activity_photo中删除
    $query = "DELETE FROM activity_time WHERE activity_id = $id LIMIT 1";
    mysqli_query($dbc, $query)or die(错误2);
    //从activity_tag中删除
    $query = "DELETE FROM activity_tag WHERE activity_id = $id ";

    mysqli_query($dbc, $query)or die(错误3);
    $query = "DELETE FROM activity_comment WHERE activity_id = $id ";
    mysqli_query($dbc, $query)or die(错误4);
    $query = "DELETE FROM activity_join WHERE activity_id = $id ";
    mysqli_query($dbc, $query)or die(错误5);
    $query = "DELETE FROM activity_love WHERE activity_id = $id ";
    mysqli_query($dbc, $query)or die(错误6);

//    $query = "DELETE FROM activity_comment WHERE activity_id = $id ";
//    mysqli_query($dbc, $query)or die(错误4);
//    // 从activity中删除
    $query = "DELETE FROM activity WHERE activity_id = $id LIMIT 1";
    mysqli_query($dbc, $query)or die(错误7);
}

$query = "DELETE FROM change_password WHERE user_id = $user_id ";
mysqli_query($dbc, $query)or die(错误6);
//$query = "DELETE FROM activity WHERE user_id = $user_id ";
//mysqli_query($dbc, $query)or die(错误1);
$query = "DELETE FROM user_comment WHERE user_id = $user_id ";
mysqli_query($dbc, $query)or die(错误7);
$query = "DELETE FROM user_comment WHERE home_user_id = $user_id ";
mysqli_query($dbc, $query)or die(错误7);
$query = "DELETE FROM portrait WHERE user_id = $user_id ";
mysqli_query($dbc, $query)or die(错误8);
$query = "DELETE FROM groups WHERE founder_id = $user_id ";
mysqli_query($dbc, $query)or die(错误9);
$query = "select attention_fan_id from attention_fan where attention_id = $user_id ";
$result = mysqli_query($dbc, $query)or die(错误6);
$attention_fan_array = array();
$attention_fan_index = 0;
while($row = mysqli_fetch_array($result,MYSQL_BOTH)){
    $attention_fan_array[$attention_fan_index++] = $row['attention_fan_id'];
}
$query = "select attention_fan_id from attention_fan where fan_id = $user_id ";
$result = mysqli_query($dbc, $query)or die(错误44);
while($row = mysqli_fetch_array($result,MYSQL_BOTH)){
    $attention_fan_array[$attention_fan_index++] = $row['attention_fan_id'];
}
foreach($attention_fan_array as $attention_fan){
    $query = "DELETE FROM attention_groups WHERE attention_fan_id = $attention_fan ";
    mysqli_query($dbc, $query)or die(错误76);
}

$query = "DELETE FROM attention_fan WHERE attention_id = $user_id ";
mysqli_query($dbc, $query)or die(错误711);
$query = "DELETE FROM attention_fan WHERE fan_id = $user_id ";
mysqli_query($dbc, $query)or die(错误141);

//从activity_tag中删除
$query = "delete from user where user_id = $user_id ";
mysqli_query($dbc, $query)or die(错误110);
// 从activity中删除
//$query = "DELETE FROM activity WHERE activity_id = $id LIMIT 1";
//mysqli_query($dbc, $query)or die(错误4);
//$result = mysqli_query($dbc,$query)or die("shibai");
echo "成功删除用户".$nickname;
echo '<p><a href="../admin/index.php">&lt;&lt; Back to admin page</a></p>';
$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/admin/index.php';
header('Location: ' . $home_url);//直接跳转回去
?>