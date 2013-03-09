<?php
require_once "php/start-session.php";
require_once("php/util.php");
$user_id = $_SESSION['user_id'];
$dbc = mysqli_connect(host,user,password,database);
$query = "select * from user where user_id = $user_id";
$data = mysqli_query($dbc,$query);
$result = mysqli_fetch_array($data);

$nickname = $result['nickname'];
$email = $result['email'];
$sex = $result['sex'];
$academy = $result['academy'];
$intro = $result['intro'];

$query = "select * from portrait where user_id = $user_id";
$data = mysqli_query($dbc,$query);
$result = mysqli_fetch_array($data);
$portrait = $result['icon'];
$portrait_path = PORTRAIT.$portrait;

?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>宅不起 | 基本信息</title>

    <!-- 在head标签内 引入Jquery -->
    <script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
    <!-- 引入Juqery-ui custom 1.9.2-->
    <script src="js/jquery-ui-1.9.2.custom.min.js" type="text/javascript" ></script>
    <!-- 在head标签内 引入Jquery 验证插件 -->
    <script src="js/jquery.validationEngine-cn.js" type="text/javascript"></script>
    <script src="js/jquery.validationEngine.js" type="text/javascript"></script>
    <script src="js/loadCollegeHint.js" type="text/javascript"></script>

    <script type="text/javascript">  
        $(document).ready(function() {
            //验证基本信息
            $("#personal-info").validationEngine({
                promptPosition: "centerRight"   //topLeft, topRight, bottomLeft, centerRight, bottomRight
            })
        });
    </script>
    <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" media="screen" title="no title" charset="utf-8" />
    <link type="text/css" rel="stylesheet" href="css/jquery-ui-1.9.2.custom.css" />
    
    <link type="text/css" rel="stylesheet" href="css/personal-info.css"/>
    <link href="css/top-nav.css" type="text/css" rel="stylesheet" />
    <link href="css/footer.css" type="text/css" rel="stylesheet"/>
    <link href="css/setting-nav.css" type="text/css" rel="stylesheet"/>
</head>
<body>
<?php require_once("top-nav.php"); ?>
<div id="container">
    <!-- header -->
    <div class="header">
        <ul class="nav-tabs">
            <li class="active">
                <a href="#">
                    基本信息
                </a>
            </li>
            <li>
                <a href="personal-setting.php">
                    设置
                </a>
            </li>
        </ul>
    </div>
    <!-- end header -->
    <form class="personal-info" id="personal-info" enctype="multipart/form-data" action="php/personal-info.php" method="post">
        <table>
            <tbody>
            <tr>
                <td><label for="name">名称</label></td>
                <td>
                    <input id="name" type="text" name="name" value="<?php echo $nickname?>"
                    class="validate[required,custom[nickname],length[4,30]] text-input" />
                </td>
            </tr>
            <tr>
                <td><label for="email">联系邮箱</label></td>
                <td>
                    <input id="email" type="text" name="email" value="<?php echo $email?>"
                    class="validate[required,custom[email]] text-input" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="update">头像</label>
                </td>
                <td>
                    <div id="head-portrait-origin">
                        <img src="<?php echo $portrait_path?>" alt="头像"/>
                    </div>
                    <input id="update" type="file" name="portrait"/>
                </td>
                <td id="head-portrait">
                    <img src="<?php echo $portrait_path?>" alt="" id="head-portrait-small"/>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td><label for="gender">性别</label></td>
                <td id="gender">
                    <?php
                    if($sex == 0){
                        ?>
                        <input type="radio" id="boy" name="gender" value="1" />
                        <label for="boy">男</label>
                        <input type="radio" id="girl" name="gender" value="0" checked="checked"/>
                        <label for="girl">女</label>
                        <?php }else{?>
                        <input type="radio" id="boy" name="gender" value="1" checked="checked"/>
                        <label for="boy">男</label>
                        <input type="radio" id="girl" name="gender" value="0" />
                        <label for="girl">女</label>
                        <?php }?>
                </td>
            </tr>
            <tr>
                <td><label for="institude">学院</label></td>
                <td>
                    <input type="text" id="institude" name="institude" value="<?php echo $academy?>"/>
                </td>
            </tr>
            <tr>
                <td><label for="brief-intro">简介</label></td>
                <td>
                    <textarea id="brief-intro" rows="1" cols="1" name="brief-intro"><?php echo $intro?></textarea>
                    <p>(请不要超过70个字)</p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" id="save-btn" value="保存" /></td>
            </tr>
            </tbody>
        </table>
    </form>
</div>
<?php require_once("footer.php"); ?>
<script src="js/Jcrop/js/jquery.Jcrop.min.js"></script>
<script src="js/jquery.picUpdate.js"></script>
<script src="js/jquery.personal-info-pic.js"></script>
</body>
</html>