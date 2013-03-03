<?php
require_once "php/start-session.php";
require_once("php/util.php");

$user_id = $_SESSION['user_id'];
$personal_id = $_GET['id'];

$dbc = mysqli_connect(host,user,password,database);
$query = "select * from user u join portrait p on u.user_id = p.user_id where p.user_id = $personal_id";
$data = mysqli_query($dbc,$query);
$result = mysqli_fetch_array($data);
$portrait = $result['icon'];//头像
$portrait_path = PORTRAIT.$portrait;
$intro = $result['intro'];

mysqli_close($dbc);
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Personal Page</title>
    <link type="text/css" rel="stylesheet" href="css/personal-page.css"/>
</head>
<body>
<?php require_once("top-nav.php"); ?>
<div id="container">
<div id="sidebar">
    <!-- introduction -->
    <div id="introduction" value="<?php echo $personal_id;?>">
        <img src="<?php echo $portrait_path?>" alt="用户"/><br/>
        <?php if($personal_id == $user_id){?>
        <a href="personal-info.php">编辑个人资料</a>
        <?php }?>
        <div id="brief-intro">
            <h4>简介</h4>
            <p><?php echo $intro;?></p>
        </div>
    </div>
    <!-- end introduction -->

    <!-- attention -->
    <div id="attention">
        <div id="attention-person">
            <h4>
                <span>···关注的人···</span>
                <span class="more"><a>(更多)</a></span>
            </h4>
            <div class="attention-display">
                <dl>
                    <dt class="attention-head">
                        <a><img src="" alt="头像" /></a>
                    </dt>
                    <dd>
                        <a>姓名</a>
                    </dd>
                </dl>
                <dl>
                    <dt class="attention-head">
                        <a><img src="" alt="头像" /></a>
                    </dt>
                    <dd>
                        <a>姓名</a>
                    </dd>
                </dl>
                <dl>
                    <dt class="attention-head">
                        <a><img src="" alt="头像" /></a>
                    </dt>
                    <dd>
                        <a>姓名</a>
                    </dd>
                </dl>
                <dl>
                    <dt class="attention-head">
                        <a><img src="" alt="头像" /></a>
                    </dt>
                    <dd>
                        <a>姓名</a>
                    </dd>
                </dl>
                <dl>
                    <dt class="attention-head">
                        <a><img src="" alt="头像" /></a>
                    </dt>
                    <dd>
                        <a>姓名</a>
                    </dd>
                </dl>
            </div>
        </div>
        <div id="attention-host">
            <h4>
                <span>···关注的主办方···</span>
                <span class="more"><a>(更多)</a></span>
            </h4>
            <div class="attention-display">
                <dl>
                    <dt class="attention-head">
                        <a><img src="" alt="头像" /></a>
                    </dt>
                    <dd>
                        <a>姓名</a>
                    </dd>
                </dl>
            </div>
        </div>
        <div id="attention-me">
            <h4>
                <span>···粉丝···</span>
                <span class="more"><a>(更多)</a></span>
            </h4>
            <div class="attention-display">
                <dl>
                    <dt class="attention-head">
                        <a><img src="" alt="头像" /></a>
                    </dt>
                    <dd>
                        <a>姓名</a>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    <!-- end attention -->
</div>
<!-- end sidebar -->

<!-- personal-content -->
<div class="personal-content" id="personal-content">
    <!-- activity -->
    <div class="event" id="like">
        <h3>
            <span>······</span>喜欢的活动<span>······</span>
            <a class="next-btn">下一页</a>
            <a class="pre-btn">上一页</a>
        </h3>
        <div class="display">
            <ul>
                <li class="single-activity">
                    <a><img src="" alt="活动图片" /></a>
                    <h5><a>标题</a></h5>
                </li>
                <li class="single-activity">
                    <a><img src="" alt="活动图片" /></a>
                    <h5><a>标题</a></h5>
                </li>
                <li class="single-activity">
                    <a><img src="" alt="活动图片" /></a>
                    <h5><a>标题</a></h5>
                </li>
                <li class="single-activity">
                    <a><img src="" alt="活动图片" /></a>
                    <h5><a>标题</a></h5>
                </li>
            </ul>
        </div>
    </div>
    <div class="event" id="join">
        <h3>
            <span>······</span>参加的活动<span>······</span>
            <a class="next-btn">下一页</a>
            <a class="pre-btn">上一页</a>
        </h3>
        <div class="display">
            <ul>
                <li class="single-activity">
                    <a><img src="" alt="活动图片" /></a>
                    <h5><a>标题</a></h5>
                </li>
                <li class="single-activity">
                    <a><img src="" alt="活动图片"/></a>
                    <h5><a>标题</a></h5>
                </li>
            </ul>
        </div>
    </div>
    <div class="event" id="host">
        <h3>
            <span>······</span>发布的活动<span>······</span>
            <a class="next-btn">下一页</a>
            <a class="pre-btn">上一页</a>
        </h3>
        <div class="display">
            <ul>
                <li class="single-activity">
                    <a><img src="" alt="活动图片"/></a>
                    <h5><a>标题</a></h5>
                </li>
                <li class="single-activity">
                    <a><img src="" alt="活动图片"/></a>
                    <h5><a>标题</a></h5>
                </li>
            </ul>
        </div>
    </div>
    <!-- end activity -->
    <!-- leav-message -->
    <div class="leave-message" id="leave-message">
        <h2>留言</h2>
        <div id="reply-form">
            <textarea cols="1" rows="1" name="reply-input" ></textarea>
            <button class="reply-btn">留言</button>
        </div>
        <div id="reply">
            <ul>
                <li class="user-post">
                    <div class="user-subject">
                        <a href="#">
                            <img class="user-photo" alt="用户" src="#"/>
                        </a>
                        <div class="reply-detail">
                            <h3>
                                <a href="#" class="user-name">姓名1</a>
                            </h3>
                            <p class="content">评论内容.</p>
                        </div>
                        <div class="reply-time">
                            <a>回复</a>
                            10月4日 22:47
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <!-- end leave-message -->
</div>
<!-- personal-content -->
</div>
<?php require_once("footer.php"); ?>
        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.personal-page.js"></script>
</body>
</html>