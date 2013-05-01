<!DOCTYPE html>
<html>
<head>
    <title>宅不起 | 好友管理</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="css/top-nav.css" type="text/css" rel="stylesheet" />
    <link href="css/footer.css" type="text/css" rel="stylesheet"/>
    <link href="css/contacts.css" type="text/css" rel="stylesheet"/>
    <link href="js/apprise-v2.min/apprise-v2.min.css" type="text/css" rel="stylesheet"/>
    <base target="_blank" />
</head>
<body>
    <?php require_once("top-nav.php"); ?>
    <div id="container">
        <div class="fixed">
            <h1>关注我的人(<span>30</span>)</h1>
            <div id="operation-box">
                <form action="#" method="get">
                    <div>
                        <input type="text" name="friends-search" id="friends-search"/>
                        <button id="friends-search-btn" type="submit"><span></span></button>
                    </div>
                </form>
                <div class="operation add">
                    <p>关注<span id="add"></span></p>
                    <div class="group-selection">
                        <ul>
                            <li>分组1</li>
                            <li>分组2</li>
                            <li>分组1</li>
                            <li>分组2</li>
                            <li>分组1</li>
                            <li>分组2</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="sidebar">
                <a href="contacts.php">我关注的人(<span>20</span>)</a>
                <div class="tips">
                    <h3>操作提示</h3>
                    <p>
                        1.单击选项框选中目标，单击选择需要的操作
                    </p>
                    <p>
                        2.单击名字或头像可查看用户主页
                    </p>
                </div>
            </div>
        </div>
        <div id="contacts-body">
            <div class="user-display" style="float: left">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">被<span>20</span>人关注 关注<span>30</span>人</p>
            </div>
            <div class="user-display" style="float: left">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">被<span>20</span>人关注 关注<span>30</span>人</p>
            </div>
            <div class="clear">点击加载更多</div>
        </div>
        <b id="to-top"></b>
    </div>
    <?php require_once("footer.php"); ?>
    <script src="js/seajs/sea.js"
        data-main = "contacts/contacts.main.js"
        data-config = "contacts/contacts.config.js"
    ></script>
</body>
</html>