<!DOCTYPE html>
<html>
<head>
    <title>宅不起 | 密码修改成功</title>
    <link type="text/css" rel="stylesheet" href="css/top-nav.css"/>
    <link type="text/css" rel="stylesheet" href="css/footer.css"/>
    <link type="text/css" rel="stylesheet" href="css/sent-confirm.css"/>
    <link type="text/css" rel="stylesheet" href="css/reset-confirm.css"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script language="javascript">
        var cdtime = 4;
        var intervalid = setInterval("onload()",1000);
        function onload()
        {
            var cdbox = document.getElementById("cdbox");
            if(cdtime === 0){
                //跳转到指定页面代码
                window.location.href = "login.php";
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
                <h2>密码修改成功</h2>
                <p>网页会在<span id="cdbox">5</span>秒内自动跳转到登陆页面，如果没有跳转请点击<a class="tips-link" href="login.php">登陆</a></p>
                <br /><br />
                <a id="back" href="index.php" >返回首页</a>
            </div>
        </div>
    </div>
    <?php require_once("footer.php"); ?>
</body>
</html>