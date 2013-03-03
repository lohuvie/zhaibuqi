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

    $email = mysqli_real_escape_string($dbc,trim($_POST['email']));
    $passwd1 = mysqli_real_escape_string($dbc,trim($_POST['passwd']));
    $passwd2 = mysqli_real_escape_string($dbc,trim($_POST['passwd-repeat']));
    $nickname = mysqli_real_escape_string($dbc,trim($_POST['nickname']));
    $error =0;

    //判定验证码是否正确
    $user_pass_phrase = sha1($_POST['validate']);
    if ($_SESSION['pass_phrase'] == $user_pass_phrase) {
        //判定输入框是否为空
        if (!empty($email) && !empty($passwd1) && !empty($passwd2) && !empty($nickname) && ($passwd1 == $passwd2)) {
            //判定用户名是否存在
            $query = "SELECT * FROM user WHERE email = '$email'";
            $data = mysqli_query($dbc, $query)
                or die('Error querying database1');
            if (mysqli_num_rows($data) == 0) {
                $query = "insert into user(user_id,email,password,nickname,register_date) values (null,'$email',SHA('$passwd1'),'$nickname',now())";
                $result = mysqli_query($dbc,$query)
                    or die('Error querying database2');
                //注册成功后自动登录
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
                    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/index.php';
                    header('Location: ' . $home_url);
                }
            }else{
                echo "用户名已存在";
                $error =1;
                //跳转回到原页面
                $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/register.php'.'?error='.$error;
                echo"$home_url";
                header('Location: ' . $home_url);
            }
        }else{
            echo "请确保填满所有输入栏，并且两次密码输入相同";
            $error =2;
            //跳转回到原页面
            $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/register.php'.'?error='.$error;
            header('Location: ' . $home_url);
        }
    }else{
        echo "验证码输入错误，请重新输入";
        $error = 3;
        //跳转回到原页面
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/register.php'.'?error='.$error;
        header('Location: ' . $home_url);
    }
    mysqli_close($dbc);