<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>信息提示</title>
</head>
<?php
require_once ('util.php');
// Start the session
session_start();

// Clear the error message
$error = 0;
$number_of_time=0;

// If the user isn't logged in, try to log them in
    $dbc = mysqli_connect(host, user, password, database)
        or die("fail to connect");

    $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
    $passwd = mysqli_real_escape_string($dbc, trim($_POST['passwd']));
    echo $email;
    echo $passwd;
//判定验证码是否正确
    $user_pass_phrase = sha1($_POST['validate']);
if(isset($_POST['validate'])){ //看验证码是否存在
if ($_SESSION['pass_phrase'] == $user_pass_phrase) {
    if (!empty($email) && !empty($passwd)) {
        // Look up the username and password in the database
        $query = "SELECT user_id, email FROM user WHERE email = '$email' AND password = SHA('$passwd')";
        $data = mysqli_query($dbc, $query)
            or die('Error query');

        if(isset($_COOKIE['number_of_time'])){

            setcookie('number_of_time',$_COOKIE['number_of_time']+1 , time() + (60),"/");
        }else{
            setcookie('number_of_time',1 , time() + (60),"/");

        }

//        setcookie('number_of_time',1 , time() + (60*60));     //一分钟超过5次现实验证码
//        $number_of_time = $_COOKIE['number_of_time']+1;
//        setcookie('number_of_time',$number_of_time , time() + (60*60));


        if (mysqli_num_rows($data) == 1) {
            // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
            $row = mysqli_fetch_array($data,MYSQLI_BOTH);
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['email'] = $row['email'];
            if($_POST['signin-cb']=="yes"){//记住我
                setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30*12*10));    // expires in 10 years
                setcookie('email', $row['email'], time() + (60 * 60 * 24 * 30*12*10));  // expires in 10 years

            }else{
                setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
                setcookie('email', $row['email'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
            }
            $query = "SELECT user_id, email FROM user WHERE email = '$email' AND password = SHA('$passwd')";
            $data = mysqli_query($dbc, $query)
                or die('Error query');

            $query = "select login_time from user where user_id = ".$_SESSION['user_id']."";
            $data = mysqli_query($dbc,$query)
                or die('fuck');

//            $present_time = date('Y-m-d H:i:s',time());//当前时间
//              if(mysqli_num_rows($data) == 0){//如果还没有登陆过
                    $query = "update user set login_time = '$present_time' where user_id =".$_SESSION['user_id']."";
                    $data = mysqli_query($dbc,$query)or die('fuck123');;
//              }else{
//                    $result = mysqli_fetch_array($data);
//                    $portrait_old = $result['icon'];
//                    $query = "insert into user
//                     portrait set icon = '$cut_name' where user_id = $user_id";
//                    $data = mysqli_query($dbc,$query);
//
//                    }

            //跳转到首页
            $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/index.php';
            header('Location: ' . $home_url);
        }
        else {
            // The username/password are incorrect so set an error message
            $error = 1;
            //跳转到回登陆页面
            $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/login.php?error='.$error;
            header('Location: ' . $home_url);
        }
    }else {
        // The username/password weren't entered so set an error message
        $error = 2;
        //跳转到回登陆页面
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/login.php?error='.$error;
        header('Location: ' . $home_url);
    }
}else{
    echo "验证码输入错误，请重新输入";
    $error = 3;
    //跳转回到原页面
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/login.php'.'?error='.$error;
    header('Location: ' . $home_url);
}
} else{
    if (!empty($email) && !empty($passwd)) {
        // Look up the username and password in the database
        $query = "SELECT user_id, email FROM user WHERE email = '$email' AND password = SHA('$passwd')";
        $data = mysqli_query($dbc, $query)
            or die('Error query');

        if(isset($_COOKIE['number_of_time'])){

            setcookie('number_of_time',$_COOKIE['number_of_time']+1 , time() + (60),"/");
        }else{
            setcookie('number_of_time',1 , time() + (60),"/");

        }

//        setcookie('number_of_time',1 , time() + (60*60));     //一分钟超过5次现实验证码
//        $number_of_time = $_COOKIE['number_of_time']+1;
//        setcookie('number_of_time',$number_of_time , time() + (60*60));


        if (mysqli_num_rows($data) == 1) {
            // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
            $row = mysqli_fetch_array($data,MYSQLI_BOTH);
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['email'] = $row['email'];
            if($_POST['signin-cb']=="yes"){
                setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30*12*50));    // expires in 50 years
                setcookie('email', $row['email'], time() + (60 * 60 * 24 * 30*12*50));  // expires in 50 years

            }else{
                setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
                setcookie('email', $row['email'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
            }

            //跳转到首页
            $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/index.php';
            header('Location: ' . $home_url);
        }
        else {
            // The username/password are incorrect so set an error message
            $error = 1;
            //跳转到回登陆页面
            $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/login.php?error='.$error;
            header('Location: ' . $home_url);
        }
    }else {
        // The username/password weren't entered so set an error message
        $error = 2;
        //跳转到回登陆页面
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/login.php?error='.$error;
        header('Location: ' . $home_url);
    }

}
    echo $error_msg;
?>