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
require_once("vo/User.php");
require_once("vo/Activity.php");

function timetostr($date,$time_begin,$time_end){

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
    return $month.'月'.$day.'日 '.$week.' '.date("H:i",strtotime($time_begin))." - ".date("H:i",strtotime($time_end));

}

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
$ad_approved = $result['ad_approved'];

//创建者 可以 看见 未通过的活动
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

    //查询活动图片
    $photo_type = $_FILES['poster']['type'];


    $photo= time().".".substr($photo_type,6);       //上传海报名字 time()+后缀名
    $poster =  $result['photo'];
    $poster_path = UPLOAD_PATH_FRONT_TO_BACK.$poster;

    //查询活动喜欢人数 以及最近喜欢这个活动的人
    $query = "select u.user_id id,u.nickname name,p.icon icon
        from activity_love al
        join user u on al.user_id = u.user_id
        left join portrait p on u.user_id = p.user_id
        where activity_id = $activity_id order by love_time desc";
    $data = mysqli_query($dbc,$query) or die('lover error');
    $love_activity_count = mysqli_num_rows($data);
    $recent_lovers = array();
    //查询最近喜欢这个活动的人
    $i = 0;
    while($i < 5 && $result = mysqli_fetch_array($data,MYSQLI_BOTH)){
        $u = new User();
        $u->setId($result['id']);
        $u->setName($result['name']);
        $u->setPortrait($result['icon']);

        $recent_lovers[$i++] = $u;
    }
    unset($u);

    //查询参加该活动的人还参加了
    if($user_id != -1){
        $query = "select activity_id
                    from activity_join
                    where (user_id in
                    (select user_id from activity_join where activity_id = $activity_id and user_id <> $user_id))
                    and activity_id <> $activity_id";
        $data = mysqli_query($dbc,$query) or die('predict_acitivty error');
        $activity_array = array();
        while($row = mysqli_fetch_array($data,MYSQLI_BOTH)){
            $activity_array[$row['activity_id']]++;
        }

        $predict_id = 0;
        $predict_count = 0;
        foreach($activity_array as $key => $value){
            if($value > $predict_count){
                $predict_id = $key;
                $predict_count = $value;
            }
        }
        unset($key);
        unset($value);

        $predict_activity = new Activity();
        if($predict_id != 0){
            $query = "select a.activity_id,a.name,a.site,ap.photo ,
                        at.date,at.time_begin,at.time_end
                        from activity a
                        left join activity_photo ap on a.activity_id = ap.activity_id
                        left join activity_time at on a.activity_id = at.activity_id
                        where a.activity_id = $predict_id";
            $data = mysqli_query($dbc,$query) or die('find predict_acitivty error');

            if($row = mysqli_fetch_array($data,MYSQLI_BOTH)){
                $predict_activity -> setId($row['activity_id']);
                $predict_activity -> setName($row['name']);
                $predict_activity -> setSite($row['site']);
                $predict_activity -> setCover($row['photo']);
                $predict_activity -> setDate($row['date']);
                $predict_activity -> setTimeBegin($row['time_begin']);
                $predict_activity -> setTimeEnd($row['time_end']);
            }
        }
    }
    //查询活动参加人数
    $query = "select * from activity_join where activity_id = $activity_id";
    $data = mysqli_query($dbc,$query);
    $join_activity_count = mysqli_num_rows($data);
    //根据是否通过 显示活动
    if($approved == 0&&$ad_approved==0){
        //未审核 标签、用户评论、我也喜欢、我要参与按钮 不显示
        $activity_name = $activity_name."(活动审核中...)";
        $approved_message = "<div class='examining'>活动已提交，等待审核中。活动审核通过后将会有邮件通知您。</div>";
    }
    if($approved ==0&&$ad_approved==1){
        //未通过 标签、用户评论、我也喜欢、我要参与按钮 不显示
        $activity_name = $activity_name."(活动未通过...)";
        $approved_message = "<div class='examining'>经过审核,活动未通过。请重新修改后提交,感谢您的支持1。</div>";
    }
    ?>
    <div id="sidebar" >
        <div class="relevant r-person">
            <h3>-最近喜欢该活动的人-</h3>
            <div>
                <?php
                //循环显示最近喜欢该活动的人
                foreach($recent_lovers as $u){
                ?>
                    <dl>
                        <dt>
                            <a href="personal-page.php?id=<?php echo $u -> getId();?>"><img src="<?php echo UPLOAD_PORTRAIT_FRONT_TO_BACK.$u->getPortrait();?>"/></a>
                        </dt>
                        <dd><a href="personal-page.php?id=<?php echo $u -> getId();?>"><?php echo $u->getName();?></a></dd>
                    </dl>
                <?php
                }
                unset($u);
                ?>
            </div>
        </div>
        <?php if($user_id != -1){?>
            <div class="relevant r-activity">
                <h3>-参加这个活动的人还参加了-</h3>
                <!-- 只有一个不需循环 -->
                <div class="single-activity">
                    <a class="activity-pic" href="activity.php?activity=43">
                        <img src="<?php echo UPLOAD_PATH_FRONT_TO_BACK.$predict_activity->getCover();?>" alt="海报">
                    </a>
                    <a class="activity-title" href="activity.php?activity=<?php echo $predict_activity->getId();?>"><?php echo $predict_activity->getName();?></a>
                    <p>时间:<?php echo timetostr($predict_activity->getDate(),$predict_activity->getTimeBegin(),$predict_activity->getTimeEnd());?></p>
                    <p>地点:<?php echo $predict_activity->getSite();?></p>
                </div>
            </div>
        <?php }?>
    </div>
    <!-- end #sidebar -->
    <div class="article">
        <div class="event-wrap">
            <div id="poster">
                <a href="<?php echo $poster_path?>">
                    <img src="<?php echo $poster_path?>" alt="点击查看原图"/>
                </a>
            </div>
            <div id="event-info" activity="<?php echo $activity_id?>" user="<?php echo $user_id?>">
                <div class="event-info" >
                    <h1><?php echo $activity_name?></h1>
                    <div id="event-time">
                        <span class="pl">时间:  </span><?php echo timetostr($date,$time_begin,$time_end); ?>
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
                        <span class="pl">发布者:</span><a href="personal-page.php?id=<?php echo $creater_id?>"><?php echo $publisher?></a>
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
            <?php require_once("comment.php"); ?>
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
