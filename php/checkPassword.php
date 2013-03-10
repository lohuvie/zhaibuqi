<?php
header('Content-type:application/json');
require_once('start-session.php');
require_once('util.php')   ;
$passwd = $_POST['current-password'];

$user_id =  $_SESSION['user_id'];
$dbc = mysqli_connect(host, user, password, database)
    or die("fail to connect");
$query = "SELECT password FROM user WHERE  user_id = '$user_id ' AND password = SHA('$passwd')";
$data = mysqli_query($dbc, $query)
    or die('Error query');
$confirm="";

if ( mysqli_num_rows($data) == 0) {
    {
       $confirm= '"no"';
    }

} else {
     $confirm =  '"yes"';
}
echo '{'.'"confirm":'.$confirm.'}';
;

?>
