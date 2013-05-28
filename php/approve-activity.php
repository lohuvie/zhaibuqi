<?php
  require_once ('authorize.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Admin - Activities </title>
  <link rel="stylesheet" type="text/css" href="../css/style.css" />
</head>
<body>
  <h2>Admin - Activities</h2>

<?php
  require_once('util.php');
  if (isset($_GET['id']) && isset($_GET['date']) && isset($_GET['email']) && isset($_GET['title'])&&
      isset($_GET['photo'])&& isset($_GET['place'])&& isset($_GET['introduce'])) {
      // Grab the score data from the GET
      $id = $_GET['id'];
      $date = $_GET['date'];
      $email = $_GET['email'];
      $title = $_GET['title'];
      $place=$_GET['place'];
      $introduce=$_GET['introduce'];

      $photo= 'cut_'.$_GET['photo'];
  }
  else if (isset($_POST['id']) && isset($_POST['email']) && isset($_POST['title'])) {
      // Grab the score data from the POST
      $id = $_POST['id'];
      $email = $_POST['email'];
      $title = $_POST['title'];
  }
  else {
      echo '<p class="error">Sorry, no activity was specified for approval.</p>';
  }
  $dbc = mysqli_connect(host,user,password,database);
  $query = "select * from user where email = '$email'";
  $result = mysqli_query($dbc,$query);
  $userName="";
  $row = mysqli_fetch_array($result);
  $userName = $row['nickname'];

  $user_id= $row['user_id'];




//  if (isset($_POST['submit'])) {
//    if ($_POST['confirm'] == 'Yes') {
      // Connect to the database
      $dbc = mysqli_connect(host, user, password, database);

      // Approve the score by setting the approved column in the database
      $query = "UPDATE activity SET approved = 1 WHERE activity_id = $id";
      mysqli_query($dbc, $query);

        //发送邮件给用户通知用户活动通过
        require_once('class.phpmailer.php'); //下载的文件必须放在该文件所在目录

        $mail = new PHPMailer(); //建立邮件发送类
        $address =$email;
        $mail->CharSet = "utf-8";

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

        $mail->IsHTML(true); // set email format to HTML //是否使用HTML格式




        $mail->Subject = "恭喜您在宅不起的活动通过";//主题

        $mail->Body = '<html>
<body>
<table style="background:#ffffff;padding:0;border:0;width:100%;text-align:left;border-collapse:collapse;border-spacing:0;">
<tbody style="background:#ffffff;text-align:left;font-size:14px;color:#000000;font-family: "Microsoft Yahei","Helvetica Neue","Luxi Sans","DejaVu Sans",Tahoma,"Hiragino Sans GB";line-height:19px;vertical-align:middle;width:99%;">
<tr><td>尊敬的 <span style="font-weight:bold;">'.$userName.'</span> 先生/女士:</td></tr>
<tr><td><br /></td></tr>
<tr><td>您在本站新建的活动,经过管理员的的遴选和审核,已经通过<br />
<tr><td>我们相信，宅不起会在你们的带领下，会发展得更加红红火火！<br />
(pleae click on the following link to check your activity:)</td></tr>
<tr><td><br /></td></tr>
<tr><td style="font-weight:bold;"><a style="color:#84C43C" href="http://localhost/zhaibuqi/zhaibuqi/activity.php?activity='.$id.'">http://localhost/zhaibuqi/zhaibuqiactivity.php?activity='.$id.'</a></td></tr>
<tr><td><br /></td></tr>
<tr><td>如果您的email程序不支持链接点击，请将上面的地址拷贝至您的浏览器(例如IE)的地址栏进入宅不起网站</td></tr>
<tr><td><br /></td></tr>
<tr><td>感谢对宅不起的支持，再次希望您在宅不起的体验有益和愉快！</td></tr>
<tr><td style="font-size:12px;">(这是一封自动产生的email，请勿回复)</td></tr>
<tr><td><br /></td></tr>
<tr><td><br /></td></tr>
</tbody>
</table>
<hr />
<div style="background:#ffffff;text-align:left;font-size:14px;color:#000000;font-family: "Microsoft Yahei","Helvetica Neue","Luxi Sans","DejaVu Sans",Tahoma,"Hiragino Sans GB";line-height:19px;vertical-align:middle;width:99%;"><a style="color:grey;text-decoration:none;" href="http://www.zhaibuqi.com/" >宅不起 http://www.zhaibuqi.com/</a></div>
</body>
</html>'; //邮件内容

        $mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略




        if(!$mail->Send())

        {

            echo "邮件发送失败. <p>";

            echo "错误原因: " . $mail->ErrorInfo;

            exit;

        }

      mysqli_close($dbc);

      // Confirm success with the user
      echo '<p>The activity  of ' . $email . ' for ' . $title . ' was successfully approved.';
//    }
//    else {
//      echo '<p class="error">Sorry, there was a problem approving the activity.</p>';
//    }
//  }
//  else if (isset($id) && isset($title) && isset($date) && isset($email)) {
//    echo '<p>Are you sure you want to approve the following activity?</p>';
//    echo '<p><strong>Email: </strong>' . $email . '<br /><strong>Date: </strong>' . $date .
//      '<br /><strong>Title: </strong>' . $title .'<br/>' .'<strong>Place: </strong>' . $place . '<br />
//      <strong>Introduce: </strong>' . $introduce . '<br /></p>';
//    echo '<form method="post" action="approve-activity.php">';
//    echo '<img src="' .UPLOADPATH . $photo . '" width="160" alt="Activity image" /><br />';
//    echo '<input type="radio" name="confirm" value="Yes" /> Yes ';
//    echo '<input type="radio" name="confirm" value="No" checked="checked" /> No <br />';
//    echo '<input type="submit" value="Submit" name="submit" />';
//    echo '<input type="hidden" name="id" value="' . $id . '" />';
//    echo '<input type="hidden" name="email" value="' . $email . '" />';
//    echo '<input type="hidden" name="title" value="' . $title . '" />';
//    echo '</form>';
//  }

  echo '<p><a href="../admin/index.php">&lt;&lt; Back to admin page</a></p>';
  $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/admin/index.php';
  header('Location: ' . $home_url);//直接跳转回去
?>

</body> 
</html>
