<?php
$space_time = $_POST['not-use-time'];
require_once('util.php');
require_once('start-session.php');

$dbc = mysqli_connect(host, user, password, database)
    or die("fail to connect");
$query = "update user set space_time = '$space_time' where user_id =".$_SESSION['user_id']."";
$data= mysqli_query($dbc,$query);
mysqli_close($dbc);
echo'保存成功';
?>
/**
 * Created by JetBrains PhpStorm.
 * User: lkjxa186
 * Date: 13-3-31
 * Time: 下午3:42
 * To change this template use File | Settings | File Templates.
 */