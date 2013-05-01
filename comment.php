<?php
require_once"util.php";
require_once"start-session.php";

function curPageSelfUrl()
{
//    $pageURL = 'http';
//
//    if ($_SERVER["HTTPS"] == "on")
//    {
//        $pageURL .= "s";
//    }
//    $pageURL .= "://";
//
//    if ($_SERVER["SERVER_PORT"] != "80")
//    {
//        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
//    }
//    else
//    {
//        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
//    }

    $php_self = $_SERVER['PHP_SELF'];

    return $php_self;
}

$dbc = mysqli_connect(host,user,password,database);
$page_id = $_GET['id'];//当前页面的id
$querry = "select * from user_comment where home_user_id ='$page_id' ORDER BY time asc";//按时间排序
$result = mysqli_query($dbc,$query);

$num=mysqli_num_rows($result);

while(mysqli_num_rows($result)){
    $row = mysql_fetch_array($result);
//    $userName = $row['nickname'];
    $user_id =$row['user_id'];//用户id
    $comment =$row['comment'];//用户的评论
    $parent_id=$row['parent_id'];//是否有父母

    $month = date("m",strtotime($time));
    $day = date("d",strtotime($time));
    $Time = $date("H:i",strtotime($time));
    $querry = "select * from user where user_id ='$user_id'";
    $result = mysqli_query($dbc,$query);
    $row = mysql_fetch_array($result);
    $userName = $row['nickname'];//用户的用户名


    $querry = "select * from portrait where user_id ='$user_id'";
    $result = mysqli_query($dbc,$query);
    $row = mysql_fetch_array($result);
    $portrait = $row['icon'];
    $portrait_path = PORTRAIT.$portrait;//图片路径


?>

    <ul id="post-box">
        <li class="user-post">
            <a href="/personal-page.php?id=<?php echo $user_id; ?>">
                <img class="user-photo" alt="用户" src="<?php echo $portrait_path;?>"/>
            </a>
            <div class="arrow-box">
                <div class="arrow"></div>
            </div>
            <div class="reply-detail">
                <h3 class="comment-header">
                    <a href="/personal-page.php?id=<?php echo $user_id; ?>" class="user-name"><?php echo $userName;?></a>
                    <span class="reply-time">
                       <?php echo $month; ?>月<?php echo $day;?>日<?php echo $Time; ?>
                    </span>
                    <span class="reply-btn" data-user-id="<?php echo $user_id;?>">回复</span>
                </h3>

                <?php
                if($parent_id ==-1){?>
                    <p class="content"><?php echo $comment; ?></p>
                <?php}else{
                    $querry = "select * from user where user_id ='$parent_id'";
                    $result = mysqli_query($dbc,$query);
                    $row = mysql_fetch_array($result);
                    $userName = $row['nickname'];//用户的从属用户名
                ?>
                <p class="content">回复<a href="/personal-page.php?id=<?php echo $parent_id; ?>" class="replyee-name"><?php echo $userName?></a>:<?php echo $comment?>.</p>
                <?php }?>
            </div>
        </li>

        <?php if($num>2){?>
            <li class="unfold">还有100条回复</li>
        <?php

        }?>
    </ul>
    <form id="comment-form" action="php/<?php curPageSelfUrl();?>" name="comment">
        <img class="user-photo-small" src="" />
        <textarea cols="1" rows="1" class="comment-content" name="reply-input" ></textarea>
        <span class="tips"></span>
        <button class="send-btn">评论</button>
    </form>

<?php

}

?>



