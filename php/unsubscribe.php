<?php
require_once('util.php');
$encrypt_email = base64_decode($_GET['p']);
$dbc = mysqli_connect(host,user,password,database)
    or die ('Error connecting to mysql server');
$query = "update user set space_time = 0 where email ='$encrypt_email'";
$data= mysqli_query($dbc,$query);
mysql_close($dbc);
$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/unsubscribe.php';
header('Location: ' . $home_url);

?>
/**
 * Created by JetBrains PhpStorm.
 * User: lkjxa186
 * Date: 13-3-31
 * Time: 下午6:40
 * To change this template use File | Settings | File Templates.
 */