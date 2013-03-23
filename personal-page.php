<?php
require_once "php/start-session.php";
require_once("php/util.php");

$personal_id = $_GET['id'];
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = USER_NO_LOGIN;
}


$dbc = mysqli_connect(host,user,password,database);
$query = "select * from user u left join portrait p on u.user_id = p.user_id where u.user_id = $personal_id";
$data = mysqli_query($dbc,$query);
$result = mysqli_fetch_array($data,MYSQLI_BOTH);

if(!empty($result)){
//如果person_id存在 显示用户
$name = $result['nickname'];
$portrait = $result['icon'];//头像
$portrait_path = PORTRAIT.$portrait;
$intro = $result['intro'];

//判断用户关系
$attention_status = 0;
$query = "select * from attention_fan
            where (attention_id = $personal_id and fan_id = $user_id)
            or (attention_id = $user_id and fan_id = $personal_id)";
$result = mysqli_query($dbc,$query);
$data = mysqli_fetch_array($result,MYSQLI_BOTH);
if(empty($data)){
    //用户之间无关系
    $attention_status = ATTENTION_NO;
}else{
    //用户之间有关系
    $attention_id = $data['attention_id'];
    $fan_id = $data['fan_id'];
    $status = $data['status'];
    if($status == ATTENTION_EACH_OTHER){
        //用户间互相关注
        $attention_status = ATTENTION_EACH_OTHER;
    }else if(($attention_id == $personal_id && $fan_id == $user_id && $status = ATTENTION_ONLY)||($attention_id == $user_id && $fan_id == $personal_id && $status == ATTENTION_BY)){
        $attention_status = ATTENTION_ONLY;
    }else if(($attention_id == $user_id && $fan_id == $personal_id && $status = ATTENTION_ONLY)||($attention_id == $personal_id && $fan_id == $user_id && $status == ATTENTION_BY)){
        $attention_status = ATTENTION_BY;
    }else{
        $attention_status = DATABASE_ERROR;
    }
}
//显示关注的人和粉丝图片及姓名
$query = "select *  from portrait p1
            join user u1 on u1.user_id = p1.user_id
            join attention_fan af on af.attention_id = u1.user_id
            join user u2 on af.fan_id = u2.user_id
            join portrait p2 on u2.user_id = p2.user_id
            where af.fan_id = $personal_id";
$result = mysqli_query($dbc,$query);
$attention_ids = Array();
$fan_ids = Array();
$attention_ids_count = 0;
$fan_ids_count = 0;
if(!empty($result)){
    while($data = mysqli_fetch_array($result,MYSQLI_BOTH)){
        if(($data['fan_id'] == $personal_id && $data['status'] == ATTENTION_ONLY) || $data['status'] == ATTENTION_EACH_OTHER){
            //用户关注列表
            $attention_ids[$attention_ids_count] =array ('name'=>$data['u1.nickname'],'portrait'=>$data['p1.icon']);
            $attention_ids_count++;
        }else{
            //用户粉丝列表
            $fan_ids[$fan_ids_count] =array ('name'=>$data['u2.nickname'],'portrait'=>$data['p2.icon']);
            $attention_ids_count++;
        }
    }
}




mysqli_close($dbc);
?>
<!DOCTYPE html>
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
        <img src="<?php echo $portrait_path?>" alt="用户"/>
        <h3 class="namebox"><?php echo $name?></h3><br/>
        <?php
        //判定显示 个人编辑资料 还是 是否关注此人
        if($personal_id == $user_id){
            echo "<a href=\"personal-info.php\">编辑个人资料</a>";
        }else{
            switch($attention_status){
                case ATTENTION_NO: echo "<a value=\"".$attention_status."\" class=\"follow\">关注此人+</a>";
                    break;
                case ATTENTION_BY: echo "<a value=\"".$attention_status."\" class=\"follow\">关注此人+</a>";
                    break;
                case ATTENTION_ONLY: echo "<a value=\"".$attention_status."\" class=\"follow\">取消关注</a>";
                    break;
                case ATTENTION_EACH_OTHER: echo "<a value=\"".$attention_status."\" class=\"follow\">取消关注</a>";
                    break;
                default:echo "<a value=\"".$attention_status."\" class=\"follow\">关注此人+</a>";
                    break;
            }
        }
        ?>
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
<?php
}else{
    //进去404页面
    echo "你访问的用户不存在";
}
?>
<?php require_once("footer.php"); ?>
        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.personal-page.js"></script>
        <script type="text/javascript" src="js/jquery.following.js"></script>
</body>
</html>