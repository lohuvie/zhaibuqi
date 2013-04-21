<?php
session_start();
//echo dirname(dirname($_SERVER['PHP_SELF']));
if(isset($_SESSION['user_id'])){
//    $home_url ='http://'.$_SERVER['HTTP_HOST'].dirname(dirnamen($_SERVER['PHP_SELF'])).'/index.php';
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(($_SERVER['PHP_SELF'])) . '/index.php';
//    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/index.php';
    header('Location: ' . $home_url);
    exit;
}
$error_message = "";

$error  = $_GET['error'];

if(isset($error)){
    switch ($error){
        case 0: $error_message ="";
            break;
        case 1: $error_message ="对不起，您输入的密码或用户名有错";
            break;
        case 2: $error_message ="对不起，您必须输入有效邮箱和密码来登录";
            break;
        case 3: $error_message ="验证码输入错误，请重新输入";
            break;

    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>宅不起 | 登陆</title>
    
    <!-- 在head标签内 引入Jquery -->
    <script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
    <!-- 在head标签内 引入Jquery 验证插件 -->
    <script src="js/jquery.validationEngine.js" type="text/javascript"></script>

    <script type="text/javascript">
    	$(document).ready(function(){
    		//验证loginForm
			$("#loginForm").validationEngine()	
    	});
    </script>

    <link href="css/validationEngine.jquery.css" type="text/css" rel="stylesheet" media="screen" title="no title" charset="utf-8" />
    <link href="css/login.css" type="text/css" rel="stylesheet"/>
    <link href="css/footer-bar.css" type="text/css" rel="stylesheet"/>

</head>
<body>
	<div class="container">
		<div class="header-bar">
			<div class="header-content-clearfix">
                <a href="index.php"><img class="logo" alt="宅不起" src="images/header-photo.png"/></a>
				<span class="signup-button">第一次使用宅不起？
					<a id="link-signup" class="sing-up-btn" href="register.php"> 创建帐户 </a>
				</span>
			</div>
		</div>
		<div class="main-content">
			<div class="main-content-clearfix">
				<div class="center-parent">
					<div id="center-parent-clearfix">
						<div id="right-signin">
						    <div id="singin-content">
						        <h2>登陆</h2>
								<form id ="loginForm" action="php/login.php" method="post">
									<div id="signin-form">
										<label for="email">登录邮箱</label>
										<br />
										<br />
										<input name="email" id="email" type="text" class="validate[required,custom[email]] text-input"/>
										<br />
										<br />
										<label for="passwd">登陆密码</label>
										<br />
										<br />
										<input name="passwd" id="passwd" type="password" class="validate[required,minSize[6],maxSize[18]] text-input"/>
										<br />
										<br />
                                        <?php  if(isset($_COOKIE['number_of_time'])){
                                        if( $_COOKIE['number_of_time']>3){?>
                                            <div>
                                                <label for="validate">验证码</label>

                                                <input name="validate" id="validate" type="text" class="validate[required] text-input"/>
                                            </div>
                                            <div>
                                                <img src="php/captcha.php" alt="验证码" id="captcha" onclick="document.getElementById('captcha').setAttribute('src','php/captcha.php')"/>
                                            </div>
                                                                          <?php } }?>

										<!--验证码隐藏部分   待修改-->                 
										<!--
										<label for="validate">验证码</label>
										<input name="validate" id="validate" type="text" value="验证码(点击刷新)"/>
										-->
										<div class="signin-checkbox">
											<input value="yes" id="signin-cb" type="checkbox" name="signin-cb"/>
											<label for="signin-cb">记住我</label>
										</div>
                                        <div id="error"><?php echo"$error_message";?></div>
										<div>
											<button class="submit-btn" type="submit" name="submit-btn">登陆</button>
										</div>
										<br />
										<a id="forget" href="get-password.php">忘记密码?</a>
									</div>
								</form>
							</div>
						</div>
						<div id="left-brand">
							<div id="brand-photo">
								<img src="images/brand-photo.jpg" width="475" alt="宅不起"/>
							</div>
							<div id="brand-words">
								<p id="brand-title">您的账户</p>
								<p id="small-title">完整的个人信息可以让我们更了解您</p>
							</div>							
						</div>	
					</div>
				</div>
				<?php require_once("footer-bar.php");?>
			</div>
		</div>
	</div>
</body>
</html>