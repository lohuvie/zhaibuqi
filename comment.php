<?php

require_once("php/util.php");
require_once("php/start-session.php");
$error=0;
$error = $_GET['error'];

function curPageSelfUrl()
{

    $php_self = $_SERVER['PHP_SELF'];
    if(strpos($php_self,"personal-page")==false){//获取第一个匹配的位置或者返回false
        $php_self="activity_comment.php";
    }else{
        $php_self="user_comment.php";
    }

    return $php_self;
}
$url = curPageSelfUrl();

$dbc = mysqli_connect(host,user,password,database);

if($url=="user_comment.php"){
    $page_id = $_GET['id'];
    $query = "select * from user_comment   where home_user_id ='$page_id' ORDER BY time asc";//按时间排序
}else{
    $page_id = $_GET['activity'];
    $query = "select * from activity_comment  where activity_id ='$page_id' ORDER BY publish_time asc";//按时间排序


}//当前页面的id
$_SESSION['comment_id'] = $page_id;

$result = mysqli_query($dbc,$query);


$num=mysqli_num_rows($result);//获得评论条数
$_SESSION['num']=$num;
if(isset($_GET['getMore'])){
  $m=$num;
}else{
    $m=3;
}
?>
<ul id="post-box">
<?php
    for($count=1;$count<$m;$count++){
        if($count ==2&&!isset($_GET['getMore'])){
            if($url=="user_comment.php"){
            $query = "select * from user_comment where home_user_id ='$page_id' ORDER BY time desc ";//按时间倒排序
            }else{
                $query = "select * from activity_comment where activity_id ='$page_id' ORDER BY publish_time desc ";//按时间倒排序

            }
            $result = mysqli_query($dbc,$query);

        }
while( $row = mysqli_fetch_array($result)){

//    $userName = $row['nickname'];
    $user_id =$row['user_id'];//用户id
    $comment =$row['comment'];//用户的评论
    $parent_id=$row['parent_id'];//是否有父母
    if($url=="user_comment.php"){
    $time=$row['time'];//上传时间
    }else{
        $time=$row['publish_time'];//上传时间

    }
//    $month = date("m",strtotime($time));
//    $day = date("d",strtotime($time));
//    $Time = date("H:i",strtotime($time));
    $query = "select * from user where user_id ='$user_id'";
    $result = mysqli_query($dbc,$query);
    $row = mysqli_fetch_array($result);
    $userName = $row['nickname'];//用户的用户名

    $query = "select * from portrait where user_id ='$user_id'";
    $result = mysqli_query($dbc,$query);
    $row = mysqli_fetch_array($result);
    $portrait = $row['icon'];
    $portrait_path = PORTRAIT.$portrait;//图片路径


?>
        <li class="user-post">
            <a href="personal-page.php?id=<?php echo $user_id; ?>">
                <img class="user-photo" alt="用户" src="<?php echo $portrait_path;?>"/>
            </a>
            <div class="arrow-box">
                <div class="arrow"></div>
            </div>
            <div class="reply-detail">
                <h3 class="comment-header">
                    <a href="personal-page.php?id=<?php echo $user_id; ?>" class="user-name"><?php echo $userName;?></a>
                    <span class="reply-time">
                       <?php echo $time; ?>
                    </span>
                    <span class="reply-btn" data-user-id="<?php echo $user_id;?>">回复</span>
                </h3>

                <?php
                if($parent_id ==0){?>
                    <p class="content"><?php echo $comment; ?></p>
                <?php }else{
                    $query = "select * from user where user_id ='$parent_id'";
                    $result = mysqli_query($dbc,$query);
                    $row = mysqli_fetch_array($result);
                    $userName = $row['nickname'];//用户的从属用户名
                ?>
                <p class="content">回复<a href="personal-page.php?id=<?php echo $parent_id; ?>" class="replyee-name"><?php echo $userName?></a>:<?php echo $comment?>.</p>
                <?php } ?>
            </div>
        </li>

        <?php if($num>2&&$count==1){ ?>
            <li class="unfold">加载更多...</li>
        <?php
            }
        }
    }
    $query = "select icon from portrait where user_id = ".$_SESSION['user_id']."";
    $result = mysqli_query($dbc,$query);
    $row = mysqli_fetch_array($result);
    $user_icon = $row['icon'];//用户的从属用户名
?>
</ul>
<form id="comment-form" action="php/<?php echo $url;?>" name="comment">
    <img class="user-photo-small" src="<?php echo UPLOAD_PORTRAIT_FRONT_TO_BACK.$user_icon;?>" />
    <textarea cols="1" rows="1" class="comment-content" name="reply-input" ></textarea>
    <span class="tips"></span>
    <button class="send-btn">评论</button>

</form>



