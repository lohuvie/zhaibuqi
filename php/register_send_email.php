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
$domain_name="";

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
            $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/register_send_email.php'.'?error='.$error;
            header('Location: ' . $home_url);
//            $query = "insert into user(user_id,email,password,nickname,register_date) values (null,'$email',SHA('$passwd1'),'$nickname',now())";
//            $result = mysqli_query($dbc,$query)
//                or die('Error querying database2');
//            //注册成功后自动登录
//            $query = "SELECT user_id, email FROM user WHERE email = '$email' AND password = SHA('$passwd1')";
//            $data = mysqli_query($dbc, $query)
//                or die('Error query');
//
//            if (mysqli_num_rows($data) == 1) {
//                // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
//                $row = mysqli_fetch_array($data);
//                $_SESSION['user_id'] = $row['user_id'];
//                $_SESSION['email'] = $row['email'];
//                setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
//                setcookie('email', $row['email'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
//                //跳转到首页
//                $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/index.php';
//                header('Location: ' . $home_url);
//            }
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

//$dbc = mysqli_connect(host,user,password,database);
//$query = "select * from user where email = '$email'";
//$result = mysqli_query($dbc,$query);
//$userName="";
//$number=0;
//$user_id="";
//$present_time =  date('Y-m-d H:i:s',time());
//if (mysqli_num_rows($result) == 1) {
//
//    $row = mysqli_fetch_array($result);
//    $userName = $row['nickname'];
////      echo'1'. $userName.'2';
//    $passwords = $row['password'];
//    $user_id= $row['user_id'];
//}


//现在我们可以发送邮件给用户了。当然，我们还得需要另一个密码重设程序 resetUserPass.php

//setcookie('user_id_number',($user_id.",".$number),time()+(60*60*24));

//if(isset($_COOKIE['user_id_number'])){
//
//    setcookie('user_id_number',$_COOKIE['user_id_number']+1 , time() + (60*60*24),"/");
//}else{
//    setcookie('user_id_number',1 , time() +(60*60*24),"/");
//
//}
$x = md5($email);
//$query = "UPDATE user set number = '".$_COOKIE['user_id_number']."' where user_id = $user_id";
//$result = mysqli_query($dbc,$query) ;//向数据库中加入number
$String = base64_encode($email.",".$x.",".$_COOKIE['user_id_number'].",".$present_time);



//echo "<html><body>";
$recipient = $email;  //收件人
$subject = "请激活你的帐号，完成注册";//主题
$message = "尊敬的".$nickname."先生/女士:

欢迎加入宅不起!

请点击下面的链接完成注册：

http://localhost/zhaibuqi/zhaibuqi/php/register_send_email.php?p=$String

如果以上链接无法点击，请将上面的地址复制到你的浏览器(如IE)的地址栏进入宅不起。

感谢对宅不起的支持，再次希望您在宅不起的体验有益和愉快！

宅不起 http://www.zhaibuqi.com/
--宅不起

(这是一封自动产生的email，请勿回复.)";

$extra = "495315864@qq.com ";
mail ($recipient, $subject, $message,'From:'. $extra);

$home_url = 'http://'. $_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['PHP_SELF'])).'/register-confirm.html?email='.$email.'&domain_name='.$domain_name;
header('Location:'.$home_url);
mysqli_close($dbc);

//echo "请到 ".$userName." 查阅来自豆瓣的邮件, 从邮件重设你的密码。";
//echo "<body/><html/>";
?>