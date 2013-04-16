<?php
require_once "php/start-session.php";
?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="Keywords" content="宅不起 大学生 活动 推送" />
    <meta name="description" content="宅不起 大学生 活动 推送" />
    <title>宅不起 | 活动介绍</title>
    <link href="css/activity.css" type="text/css" rel="stylesheet" />
    <link href="css/comment.css" type="text/css" rel="stylesheet" />
    <script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="js/even-act.js" type="text/javascript" ></script>
    <!--[if IE]>
    <style type="text/css">
            /* 请将所有版本的 IE 的 css 修复放在这个条件注释中 */
    </style>
    <![endif]-->
</head>

<body>
<?php require_once("top-nav.php");?>

<div id="container">
<?php
require_once("php/util.php");
//获取活动ID
$activity_id = $_GET['activity'];
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = -1;
}
$dbc = mysqli_connect(host,user,password,database);


$query = "select * from user u
join activity a on u.user_id = a.user_id
left join activity_time atime on a.activity_id = atime.activity_id
left join activity_photo ap on a.activity_id = ap.activity_id
where a.activity_id = $activity_id";
$data = mysqli_query($dbc,$query);
$result = mysqli_fetch_array($data,MYSQLI_BOTH);

$creater_id = $result['user_id'];
$approved = $result['approved'];
if($user_id == $creater_id || $approved != 0){
    $activity_name = $result['name'];
    $site = $result['site'];
    $introduce = $result['introduce'];

    if($result['cost'] == 0){
        $cost = "免费";
    }else{
        $cost = "收费";
    }

    $type = '';
    switch($result['type']){
        case 'match' : $type = '比赛';
            break;
        case 'lecture' : $type = '讲座';
            break;
        case 'play' : $type = '出去耍';
            break;
        case 'club' : $type = '社团活动';
            break;
    }

    //查询用户
    $publisher = $result['nickname'];
    $email = $result['email'];

    //查询活动时间
    $date = $result['date'];
    $time_begin = $result['time_begin'];
    $time_end = $result['time_end'];

    //计算星期几 月 日
    $month = date("m",strtotime($date));
    $day = date("d",strtotime($date));
    switch(date("w",strtotime($date))){
        case 0: $week = '周日';
            break;
        case 1: $week = '周一';
            break;
        case 2: $week = '周二';
            break;
        case 3: $week = '周三';
            break;
        case 4: $week = '周四';
            break;
        case 5: $week = '周五';
            break;
        case 6: $week = '周六';
            break;
    }

    //查询活动图片
    $poster =  $result['photo'];
    $poster_path = UPLOAD_PATH_FRONT_TO_BACK.$poster;

    //查询活动喜欢人数
    $query = "select * from activity_love where activity_id = $activity_id";
    $data = mysqli_query($dbc,$query);
    $love_activity_count = mysqli_num_rows($data);

    //查询活动参加人数
    $query = "select * from activity_join where activity_id = $activity_id";
    $data = mysqli_query($dbc,$query);
    $join_activity_count = mysqli_num_rows($data);
    //根据是否通过 显示活动
    if($approved == 0){
        //未通过 标签、用户评论、我也喜欢、我要参与按钮 不显示
        $activity_name = $activity_name."(活动审核中..)";
        $approved_message = "活动已创建，等待审核中... 审核通过后会有邮件通知";
    }
    ?>
    <div id="sidebar" >
        <div id="relevant-activity">
            <h3>相关活动</h3>
            <p>此 div 上显示相关活动
            </p>
        </div>
        <div id="recently-like">
            <h3>最近喜欢这个活动的人</h3>
            <p>此 div 上显示最近喜欢这个活动的人
            </p>
        </div>
    </div>
    <!-- end #sidebar -->
    <div class="article">
        <div class="event-wrap">
            <div id="poster">
                <a href="#">
                    <img src="<?php echo $poster_path?>" alt="活动海报" width="215"/>
                </a>
            </div>
            <div id="event-info" activity="<?php echo $activity_id?>" user="<?php echo $user_id?>">
                <div class="event-info" >
                    <h1><?php echo $activity_name?></h1>
                    <div id="event-time">
                        <span class="pl">时间:  </span><?php echo $month.'月'.$day.'日 '.$week.' '.date("H:i",strtotime($time_begin))." - ".date("H:i",strtotime($time_end)); ?>
                    </div>
                    <div id="event-location">
                        <span class="pl">地点:  </span><?php echo $site?>
                    </div>
                    <div id="event-fee">
                        <span class="pl">费用:  </span><?php echo $cost?>
                    </div>
                    <div id="event-category">
                        <span class="pl">类型:  </span><?php echo $type?>
                    </div>
                    <div id="event-host">
                        <span class="pl">发布者:</span><?php echo $publisher?>
                    </div>
                    <div class="interest-attend pl">
                        <span class="num" id="love-num"></span>
                        <span class="pl">人喜欢   </span>
                        <span class="num" id="join-num"></span>
                        <span class="pl">人参加</span>
                    </div>
                </div>
                <?php if($approved != 0){?>
                <div id="event-act">
                    <a class="collect-btn" id="love">
                        <span>我也喜欢</span>
                    </a>
                    <a class="collect-btn" id="join">
                        <span>我要参与</span>
                    </a>
                </div>
                <?php }?>
                <!--
                        <div class="events-spread">
                            分享
                        </div>
                        -->
            </div>
        </div>
        <?php if($approved != 0){?>
        <div class="event-tags">
            <span class="tags-left">标签:</span>
            <ul>
                <?php
                //标签显示
                $query = "select t.name tag_name from activity a join activity_tag actt join tag t where a.activity_id = actt.activity_id and actt.tag_id = t.tag_id and  a.activity_id = $activity_id";
                $data = mysqli_query($dbc,$query);
                while($result = mysqli_fetch_array($data))
                {
                    echo "<li><span><a href='#'>".$result['tag_name']."</a></span></li>";
                }
                ?>
            </ul>
        </div>
        <?php }?>
        <span id=approve><?php echo $approved_message?></span>
        <div class="related-info">
            <h2>活动详情</h2>
            <div id="info-detail" >
                <p>
                    <?php echo $introduce?>
                </p>
            </div>
            <?php if($approved != 0){?>
            <h2>用户评论</h2>
            <?php require_once("comment.html"); ?>
            <?php }?>
        </div>
    </div>
<?php
}else{
    //审核未通过 非用户本人无法查看
    require_once("404.php");
}
?>
</div>
<?php require_once("footer.php"); ?>
<script src="js/jquery.comment.js"></script>
</body>
</html>
