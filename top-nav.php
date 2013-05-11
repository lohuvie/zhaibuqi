
<head>
    <link href="css/top-nav.css" type="text/css" rel="stylesheet" />
</head>
<?php
require_once('php/start-session.php');
//get present url
function curPageURL()
{
    $pageURL = 'http';

    if ($_SERVER["HTTPS"] == "on")
    {
        $pageURL .= "s";
    }
    $pageURL .= "://";

    if ($_SERVER["SERVER_PORT"] != "80")
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    }
    else
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

$URL = curPageURL();

setcookie('URL',$URL, time() + (60));    // expires in 1 MINUTE
?>
<div class="top-nav">
    <div id="nav-wrapper">
        <!-- Menu Content Starts Here -->
        <div class="menu-items">
            <div id="logo">
               <a href="index.php"><img src="images/logo.png" alt="宅不起"/></a>
            </div>
            <ul>
                <li class="menu-items-home">
                    <a href="index.php">首页</a>
                </li>
                <li>
                    <?php
                        if(isset ($_SESSION['user_id'])){
                    ?>
                    <a href="personal-page.php?id=<?php echo $_SESSION['user_id']; ?>">个人主页</a>
                    <?php
                        }else{
                    ?>
                    <a href="login.php">个人主页</a>
                    <?php }?>
                </li>
            </ul>
            <?php
                require_once "php/util.php";
                if(!isset ($_SESSION['user_id'])){
            ?>
            <div class="top-nav-info">
                <a href="register.php">注册</a>
                <a id="login" rel="nofollow" href="login.php">登录</a>
            </div>
            <?php
                } else{
                    $id = $_SESSION['user_id'];
                    //查询用户昵称显示在导航栏中
                    $dbc = mysqli_connect(host, user, password, database);
                    $query = "select nickname from user where user_id = $id";
                    $data = mysqli_query($dbc,$query);
                    $result = mysqli_fetch_array($data);
                    $nickname = $result['nickname'];
            ?>
            <div class="top-nav-info">
                <a rel="nofollow" href="php/logout.php">注销</a>
                <a href="personal-info.php"><?php echo $nickname ?></a>
            </div>
            <?php
                }
            ?>
            <!-- Search Part Starts Here -->
            <div class="search-body">
                <form action="" method="get" id="search-form">
                    <div>
                        <input type="text" id="search-text" name="search-text"/>
                        <input type="image" alt="搜索" src="images/search_btn.gif" name="search-btn" id="search-btn" />
                    </div>
                </form>
            </div>
        <!-- Menu Content Ends here -->
        </div>
    </div>
</div>
