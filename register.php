<?php
$error_message = "";
$error  = $_GET['error'];
switch ($error){
    case 0: $error_message ="";
        break;
    case 1: $error_message ="用户名已存在";
        break;
    case 2: $error_message ="请确保填满所有输入栏，并且两次密码输入相同";
        break;
    case 3: $error_message ="验证码输入错误，请重新输入";
        break;
}
?>

<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>注册宅不起账号</title>

    <!-- 在head标签内 引入Jquery -->
    <script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
    <!-- 在head标签内 引入Jquery 验证插件 -->
    <script src="js/jquery.validationEngine.js" type="text/javascript"></script>
    <script src="js/jquery.register.js" type="text/javascript"></script>
    <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" media="screen" title="no title" charset="utf-8" />

    <link type="text/css" rel="stylesheet" href="css/register.css"/>
    <link href="css/footer-bar.css" type="text/css" rel="stylesheet"/>   
</head>
<body>
    <div class="header-bar">
        <div class="header-content-clearfix">
            <a href="index.php"><img class="logo" alt="宅不起" src="images/header-photo.png"/></a>
    		<span class="signup-button">已有账号？
    			<a id="link-signup" class="sing-up-btn" href="login.php"> 登陆 </a>
    		</span>
        </div>
    </div>
  <div id="container">
      <div class="tips">
          <div class="single-tips">
              <div class="tips-content" id="tips-content1">
                  <h3>加入 我们!</h3>
                  <p>"宅不起"的建立将汇集校园丰富的活动信息。加入"宅不起"，离开你的寝室！在这里，你一定能找到适合你们的活动和志同道合的朋友。</p>
              </div>
              <img src="images/register-photo.jpg" alt="" width="245"/>
          </div>
          <div class="single-tips">
              <div class="tips-content" id="tips-content2">
                  <h3>关于 "宅不起"</h3>
                  <p>"宅不起"以分享活动和建立有共同爱好的圈子为目的，你们可以发布有关活动的信息、评论活动以及获得推荐。</p>
              </div>
              <img src="images/register-photo2.jpg" alt=""  width="245"/>
          </div>
      </div>
      <div id="content">
          <form id="register-form" action="php/register_send_email.php" method="post">
              <!--<div id="link">
                  <a href="#" id="renren-link" class="submit-btn">用人人账号注册</a>
                  <a href="#" id="qq-link" class="submit-btn">用QQ账号注册</a>
                  <a href="#" id="sina-link" class="submit-btn">用新浪微博注册</a>
              </div>
              <div id="line">
                  <p>
                      <span>or</span>
                  </p>
              </div>-->
              <div>
                  <label for="email">登陆邮箱 <span id="error"><?php echo $error_message;?></span> </label>
                  <input name="email" id="email" type="text" class="validate[required,custom[email],ajax[ajaxUserCallPhp]] text-input"/>
              </div>
              <div>
                  <label for="nickname">昵称</label>
                  <input name="nickname" id="nickname" type="text" class="validate[required,custom[nickname],minSize[2],maxSize[15]] text-input"/>
              </div>
              <div>
                  <label for="passwd">设置密码</label>
                  <input name="passwd" id="passwd" type="password" class="validate[required,minSize[6],maxSize[18]] text-input" />
              </div>
              <div>
                  <label for="passwd-repeat">确认密码</label>
                  <input name="passwd-repeat" id="passwd-repeat" type="password" class="validate[required,equals[passwd]] text-input" />
              </div>
              <div>
                  <label for="validate">验证码</label>

                  <input name="validate" id="validate" type="text" class="validate[required] text-input"/>
              </div>
              <div>
                  <img src="php/captcha.php" alt="验证码" id="captcha" onclick="document.getElementById('captcha').setAttribute('src','php/captcha.php')"/>
              </div>
              <div class="agreement-box">

                  <input disabled="disabled" class="validate[required] checkbox" type="checkbox" id="agree" name="agree" checked="checked" />
                  <label for="agree">
                  我已阅读并同意宅不起《<a href="agreement.php">用户协议</a>》
                  </label>

              </div>
              <div>
                  <button type="submit" class="submit-btn" id="create-btn" name="submit-btn">创建账号</button>
              </div>

          </form>
      </div>
      <?php require_once("footer-bar.php");?>
  </div>
</body>
</html>