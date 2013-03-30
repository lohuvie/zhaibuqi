<?php
$email = $_POST['email'];
$domain_name="";
$error="";

require_once('util.php');
if((isset($_COOKIE['c'])&&($_COOKIE['c']!=$email)&&(!empty($email)))||!isset($_COOKIE['c'])){//换号换密码和第一次

    setcookie('c',$email,time()+(60*10));//10分钟

}

if(!empty($email)){
    $email = $email;
}else{
    $email = $_COOKIE['c'];


}
$domain=explode("@",$email);
$domain1 = explode(".",$domain[1]);
$domain_name = $domain1[0];//域名获取问题

$dbc = mysqli_connect(host,user,password,database);
$query = "select * from user where email = '$email'";
$result = mysqli_query($dbc,$query);
$userName="";
$number=0;
$user_id="";
$present_time =  date('Y-m-d H:i:s',time());
  if (mysqli_num_rows($result) == 1) {

      $row = mysqli_fetch_array($result);
      $userName = $row['nickname'];
//      echo'1'. $userName.'2';
      $passwords = $row['password'];
      $user_id= $row['user_id'];


$x = md5($email);
//现在我们可以发送邮件给用户了。当然，我们还得需要另一个密码重设程序 resetUserPass.php

//setcookie('user_id_number',($user_id.",".$number),time()+(60*60*24));

if(isset($_COOKIE['user_id_number'])){

    setcookie('user_id_number',$_COOKIE['user_id_number']+1 , time() + (60*60*24),"/");//发邮件的次数,位置
}else{
    setcookie('user_id_number',1 , time() +(60*60*24),"/");

}
$query = "UPDATE user set number = '".$_COOKIE['user_id_number']."' where user_id = $user_id";
$result = mysqli_query($dbc,$query) ;//向数据库中加入number
$String = base64_encode($email.",".$x.",".$_COOKIE['user_id_number'].",".$present_time);//通过链接来传递时间

$x = md5($email);

//echo "<html><body>";
//$recipient =$email;  //收件人
//$subject = "重设".$userName."在宅不起的密码";//主题
//$message = "尊敬的".$userName."先生/女士:
//
//您使用了本站提供的密码找回功能，如果你确认此密码找回功能是你启用的，请点击下面的链接
//
//(pleae click on the following link to reset your password:)
//
//http://localhost/zhaibuqi/zhaibuqi/reset-password.php?p=$String
//
//如果您的email程序不支持链接点击，请将上面的地址拷贝至您的浏览器(例如IE)的地址栏进入宅不起
//
//请在24小时内点击此改密链接，同时此链接只能使用一次，如失效请重新索取！
//
//感谢对宅不起的支持，再次希望您在宅不起的体验有益和愉快！
//
//宅不起 http://www.zhaibuqi.com/
//
//(这是一封自动产生的email，请勿回复.)";
////$message = "亲爱的".'$userName'."浪客剑心：
////
////您的密码重设要求已经得到验证。请点击以下链接输入您新的密码：
////
////(pleae click on the following link to reset your password:)
////
////https://www.douban.com/accounts/resetpassword?confirmation=56c9093daedcf553
////
////如果您的email程序不支持链接点击，请将上面的地址拷贝至您的浏览器(例如IE)的地址栏进入宅不起。
////
////感谢对宅不起的支持，再次希望您在宅不起的体验有益和愉快。
////
////宅不起 http://www.宅不起.com/
////
////(这是一封自动产生的email，请勿回复。)"; //正文
//$extra = "495315864@qq.com";
////mail ($recipient, $subject, $message,'From:'. $extra);


require_once('class.phpmailer.php'); //下载的文件必须放在该文件所在目录

$mail = new PHPMailer(); //建立邮件发送类
$address =$email;

$mail->IsSMTP(); // 使用SMTP方式发送

$mail->Host = "smtp.qq.com"; // 您的企业邮局域名

$mail->SMTPAuth = true; // 启用SMTP验证功能

$mail->Username = "541232834@qq.com"; // 邮局用户名(请填写完整的email地址)

$mail->Password = "8327ZHE782yi"; // 邮局密码

$mail->Port=25;

$mail->From = "541232834@qq.com"; //邮件发送者email地址

$mail->FromName = "浪客剑心";

$mail->AddAddress("$address", $userName);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")

//$mail->AddReplyTo("", "");



//$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件

//$mail->IsHTML(true); // set email format to HTML //是否使用HTML格式



$mail->Subject = "重设".$userName."在宅不起的密码";//主题

$mail->Body = "尊敬的".$userName."先生/女士:

您使用了本站提供的密码找回功能，如果你确认此密码找回功能是你启用的，请点击下面的链接

(pleae click on the following link to reset your password:)

http://localhost/zhaibuqi/zhaibuqi/reset-password.php?p=$String

如果您的email程序不支持链接点击，请将上面的地址拷贝至您的浏览器(例如IE)的地址栏进入宅不起

请在24小时内点击此改密链接，同时此链接只能使用一次，如失效请重新索取！

感谢对宅不起的支持，再次希望您在宅不起的体验有益和愉快！

宅不起 http://www.zhaibuqi.com/

(这是一封自动产生的email，请勿回复.)"; //邮件内容

$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略



if(!$mail->Send())

{

echo "邮件发送失败. <p>";

echo "错误原因: " . $mail->ErrorInfo;

exit;

}


$p=base64_encode($email.",".$domain_name);

$home_url = 'http://'. $_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['PHP_SELF'])).'/sent-cofirm.php?p='.$p;

header('Location:'.$home_url);



//if(@mail  ( $recipient  , $subject  , $message  , $extra  )){
//

mysqli_close($dbc);

//echo "请到 ".$userName." 查阅来自豆瓣的邮件, 从邮件重设你的密码。";
//echo "<body/><html/>";
  }else{
      $error = 1 ;
      $home_url = 'http://'. $_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['PHP_SELF'])).'/get-password.php?error='.$error;

      header('Location:'.$home_url);

  }
?>