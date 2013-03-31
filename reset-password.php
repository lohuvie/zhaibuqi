<?php
//$error_message = "";
//$error  = $_GET['error'];
//switch ($error){
//
//    case 1: $error_message ="对不起，您输入的验证码错误，请重新输入";
//    break;
////    case 2: $error_message ="对不起，您必须输入有效邮箱和密码来登录";
////    break;
////    case 3: $error_message ="验证码输入错误，请重新输入";
////    break;
//
//}
/**
 * 用base64_decode解开$_GET['p']的值
 */
require_once('php/util.php');
session_start();

$dbc = mysqli_connect(host,user,password,database);
$passwords="";
$number="";
$user_id = "";
$error1="";
$error2= "";
$error3='';
$present_time =  date('Y-m-d H:i:s',time());
$output="";
$user_exist=false;

$array = explode(',',base64_decode($_GET['p']));
echo $array['0'].'333333333';
echo '11111'.$array['1'].'222222222';
$query = "select * from user where email =  '".trim($array['0'])."'";
$result = mysqli_query($dbc,$query) ;
if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_array($result);
    $passwords = $row['password'];
    $email = $row['email'];
    $user_id = $row['user_id'];
    $number = $row['number'] ;//从数据库中选出number
    setcookie('email',$email,time()+(60*60*24));
    echo $email.'111F';
}
$query = "select * from change_password where user_id= $user_id and number = '".$_COOKIE['user_id_number']."'";
$result = mysqli_query($dbc,$query) ;
$row = mysqli_fetch_array($result);
$use_time = $row['use_time'];
$user_exist=ture;

/**
 * 这时，我们会得到一个数组，$array，里面分别存放了用户名和我们需要一段字符串
 * $array[0] 为email
 * $array[1] 为我们生成的字符串
 */
//开始进行匹配工作吧。

//$sql = "select passwords from member where username = '".trim($_array['0'])."'";
//
//$passwords = $db->GetOne($sql);

/**
 * 产生配置码
 */
$checkCode = md5($array['0']);
echo'666666666'.$checkCode.'777777777';

/**
 * 进行配置验证： =>
 */
if($use_time==1){
    $error3='重设密码的链接已使用，请重新申请';
}

if((strtotime($present_time)-strtotime($array['3']))>(60*60*24)){
    $error2='重设密码的链接已过期，请重新申请';
}
if ($array['2']!=$number){
      $error1='重设密码的链接已失效，请重新申请';
}
if(!empty($error1)||!empty($error2)||!empty($error3)){
    $output="#1";
}else $output ="php/get_password.php";
if( $array['1'] === $checkCode){?>
<!DOCTYPE html>
<html>
<head>
    <title>宅不起 | 更改密码</title>

    <!-- 在head标签内 引入Jquery -->
    <script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
    <!-- 在head标签内 引入Jquery 验证插件 -->
    <script src="js/jquery.validationEngine.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            //验证密码
            $("#sent-mail").validationEngine()
        });
    </script>
    <link type="text/css" rel="stylesheet" href="css/validationEngine.jquery.css" />

    <link type="text/css" rel="stylesheet" href="css/top-nav.css" />
    <link type="text/css" rel="stylesheet" href="css/footer.css" />
    <link type="text/css" rel="stylesheet" href="css/reset-password.css" />

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    <?php require_once("top-nav.php"); ?>
    <div id="container">
        <div id="container-clear-fix">
            <div class="aside">
                <h3>如何使密码更安全？</h3>
                <ul>
                  <li>使用标点符号、数字和大小写字母的组合作为密码。</li>
                  <li>密码中勿包含个人信息（如姓名、生日等）。</li>
                  <li>不使用和其他网站相同的密码。</li>
                  <li>定期修改密码。</li>
                </ul>
            </div>
            <div id="error1"><?php echo"$error1<br/>$error2<br/>$error3";?></div>
            <div class="password-box">
                <h2>更改密码</h2>
                <form id="sent-mail" action="<?php echo $output ?>" method="post">
                    <div class="input-box">
                        <p>新的密码</p>
                        <input id="new-password" name="new-password" type="password" size="20"
                        class="validate[required,minSize[6],maxSize[18]] text-input" />
                        <p>确认密码</p>
                        <input id="confirm-password" name="confirm-password" type="password" size="20"
                        class="validate[required,equals[new-password]] text-input" />
                    </div>
<!--                    <div>-->
<!--                        <label for="validate">验证码</label>-->
<!---->
<!--                        <input name="validate" id="validate" type="text" class="validate[required] text-input"/>-->
<!--                    </div>-->
<!--                    <div>-->
<!--                        <img src="php/captcha.php" alt="验证码" id="captcha" onclick="document.getElementById('captcha').setAttribute('src','php/captcha.php')"/>-->
<!--                    </div>-->
                    <div class="tool-bar">
                        <input type="submit" class="sent-btn" value="更改密码" />
                        <a id="back" href="login.php" >返回登陆</a>
                    </div>
                    <div id="error"><?php echo $error_message;?></div>
                </form>
            </div>
        </div>
    </div>
    <?php require_once("footer.php"); ?>
</body>
</html>
<?php }
//}if($user_exist=false){
//    echo"用户不存在";
//}
?>

<!--create table change_password-->
<!--(-->
<!--user_id            int,-->
<!--number             int,-->
<!--use_time           int,-->
<!--constraint id_number primary key (user_id,number)-->
<!--);-->