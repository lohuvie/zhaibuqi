<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>宅不起 | 页面不存在</title>
    <link href="css/404.css" type="text/css" rel="stylesheet" />
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
        cdbox.innerText = cdtime+'秒...';
        cdtime--;
    }
    </script>
    </head>
<body onload="onload()">
    <?php require_once("top-nav.php");?>
    <div id="container">
        <div id="container-clear-fix">
            <div class="textbox">
                <h1>404! 您想访问的页面<br />不不不...不存在</h1>
                <span id="cdbox"></span>
                <a href="index.php">返回首页 Zhaibuqi.com</a>
            </div>
            <img src="images/404.jpg" alt="页面不存在" width="200" height="227" />
        </div>
    </div>
    <?php require_once("footer.php");?>
</body>
</html>