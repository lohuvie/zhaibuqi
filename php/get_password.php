<?php
/**
 * 用base64_decode解开$_GET['p']的值
 */
require_once('util.php');

$dbc = mysqli_connect(host,user,password,database);
$passwords="";
$userName="";
$email=$_COOKIE['email'];
echo $email;
$error=0;
$new_password = $_POST['new-password'];
$confirm_password = $_POST['confirm-password'];
$user_pass_phrase = sha1($_POST['validate']);
$query = "select  * from user where email='$email'";
$result=mysqli_query($dbc,$query);
$row = mysqli_fetch_array($result);
$user_id = $row['user_id'];

$query = "insert into change_password(user_id,number,use_time) values($user_id,'".$_COOKIE['user_id_number']."',1)";
$result = mysqli_query($dbc,$query);


//
//$array = explode('.',base64_decode($_GET['p']));
//$query = "select * from user where email =  '$email'";
//echo $_SESSION['email'];
//$result = mysqli_query($dbc,$query) ;
//if (mysqli_num_rows($result) == 1) {
//    echo "sss";
//
//    $row = mysqli_fetch_array($result);
//
////      echo'1'. $userName.'2';
//    $passwords = $row['password'];
////    $email = $row['email'];
//}
/**
 * 这时，我们会得到一个数组，$array，里面分别存放了用户名和我们需要一段字符串
 * $array[0] 为用户名
 * $array[1] 为我们生成的字符串
 */
//开始进行匹配工作吧。

//$sql = "select passwords from member where username = '".trim($_array['0'])."'";
//
//$passwords = $db->GetOne($sql);

/**
 * 产生配置码
 */
//$checkCode = md5($array['0'].'+'.$passwords);

/**
 * 进行配置验证： =>
 */

//if( $array['1'] === $checkCode ){
    //执行重置程序，一般给出三个输入框。
//    Echo "<input name=username value='".$array['0']."' onlyread>";
//    Echo "<input name=userpasswd type=password>";
//    Echo "<input name=reinput type=password>";

    if ($_SESSION['pass_phrase'] == $user_pass_phrase) {
        $query ="UPDATE user SET password = SHA('$new_password') WHERE email= '$email'";
        $result = mysqli_query($dbc,$query) ;
        echo "sb";
    }else{
            $error=1;
            $home_url='http://'.$_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['PHP_SELF'])).'/reset-password.php?error=.$error';
            header('Location:'.$home_url);
        }

//}
  $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/login.php';
        header('Location: ' . $home_url);
     mysqli_close($dbc);
?>