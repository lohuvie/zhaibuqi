<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>信息提示</title>
</head>
<?php
    require_once("util.php");
    require_once("start-session.php");
    $user_id = $_SESSION['user_id'];
    $password = $_POST['current-password'];
    $password_new = $_POST['new-password'];
    $password_confirm = $_POST['password-confirmation'];

    //判断两次新密码是否相等
    if($password_new == $password_confirm){
        $dbc = mysqli_connect(host,user,password,database);
        $query = "SELECT user_id FROM user WHERE user_id = $user_id AND password = SHA('$password')";
        $data = mysqli_query($dbc,$query)
            or die("fuck baby");

        //判断旧密码是否正确
        if (mysqli_num_rows($data) == 1) {
            $query = "update user set password = SHA('$password_new') where user_id = $user_id";
            $data = mysqli_query($dbc,$query)
                or die("fuck");
        }else{
            echo "原密码输入错误";
        }
    }else{
        echo "两次新密码输入不一致";
    }
    //跳转到个人设置页
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/personal-setting.php';
    header('Location: ' . $home_url);

    mysqli_close($dbc);

?>