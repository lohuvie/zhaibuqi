<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>信息提示</title>
</head>
<?php
    require_once('start-session.php');
    require_once('util.php');

    $dbc = mysqli_connect(host,user,password,database)
        or die ('Error connecting to mysql server');
$array = explode(',',base64_decode($_GET['p']));
$checkCode = md5($array['0']);
$email = $array['0'];
$passwd1 = $array['3'];
$nickname= $array['2'];
$present_time = date('Y-m-d H:i:s',time());//当前时间

    //判定链接是否合法
        if ($array['1'] === $checkCode) {
            //判定用户名是否存在
            $query = "SELECT * FROM user WHERE email = '$email'";
            $data = mysqli_query($dbc, $query)
                or die('Error querying database1');
            if (mysqli_num_rows($data) == 0) {
                $query = "insert into user(user_id,email,password,nickname,register_date,login_time) values (null,'$email',SHA('$passwd1'),'$nickname',now(),'$present_time')";
                $result = mysqli_query($dbc,$query)
                    or die('Error querying database2');

            }
                //自动登录
                $query = "SELECT user_id, email FROM user WHERE email = '$email' AND password = SHA('$passwd1')";
                $data = mysqli_query($dbc, $query)
                    or die('Error query');

                if (mysqli_num_rows($data) == 1) {
                    // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
                    $row = mysqli_fetch_array($data);
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['email'] = $row['email'];
                    setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
                    setcookie('email', $row['email'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
                    //跳转到首页
                    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/activation.php';
                    header('Location: ' . $home_url);
                }
        }else echo"链接不合法";
    mysqli_close($dbc);
?>