<?php require_once "php/start-session.php";?>
<!DOCTYPE html>
<html>
<head>
    <title>宅不起 | 你的账号已激活</title>
    <link type="text/css" rel="stylesheet" href="css/top-nav.css"/>
    <link type="text/css" rel="stylesheet" href="css/footer.css"/>
    <link type="text/css" rel="stylesheet" href="css/sent-confirm.css"/>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script language="javascript">
        var cdtime = 4;
        var intervalid = setInterval("onload()",1000);
        function onload()
        {
            var cdbox = document.getElementById("cdbox");
            if(cdtime === 0){
                //跳转到指定页面代码
                window.location.href = "index.php";
                clearInterval(intervalid);
            }
            cdbox.innerText = cdtime;
            cdtime--;
        }
    </script>
</head>
<body onload="onload()">
<?php require_once("top-nav.php"); ?>
<div id="container">
    <div id="container-clear-fix">
        <div class="tips">
            <h2>账号已激活</h2>
            <p>您好，您的邮箱已激活！</p>
            <br /><br />
            <p style="color:#888888;"><span id="cdbox">5</span>秒...<a id="back" href="index.php" >返回首页</a></p>
        </div>
    </div>
</div>
<?php require_once("footer.php"); ?>
</body>
</html>
