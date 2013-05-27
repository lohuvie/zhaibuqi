<?php
$text = $_GET['search-text'];
setcookie('search_person',$text,time()+(60*60));
setcookie('search_activity',$text,time()+(60*60));//保存一小时
require_once('php/util.php');
    $dbc = mysqli_connect(host,user,password,database);
    $query = "select *
    from user left join  portrait on(user.user_id=portrait.user_id)
    where nickname LIKE '%$text%'  ";//包含搜索框的内容(名字里面)
    $result1 = mysqli_query($dbc,$query);
    $user_number = mysqli_num_rows($result1);//结果条数
//如果收缩框为空值时的处理

//活动的处理
    $query="select * from activity  a join activity_time b on a.activity_id = b.activity_id
    left  join user c on a.user_id = c.user_id
    left join activity_photo d on a.activity_id = d.activity_id
    where a.name like '%$text%' and approved =1";//多个表的链接
    $result2 = mysqli_query($dbc,$query);
    $activity_number = mysqli_num_rows($result2);//活动的查询





?><!DOCTYPE html>
<html>
<head>
    <title>宅不起 | 搜索结果</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="css/top-nav.css" type="text/css" rel="stylesheet" />
    <link href="css/footer.css" type="text/css" rel="stylesheet"/>
    <link href="css/search-activity.css" type="text/css" rel="stylesheet" />
    <link href="css/search-person.css" type="text/css" rel="stylesheet"/>
    <link href="css/search-all.css" type="text/css" rel="stylesheet"/>
</head>
<body>
    <?php require_once("top-nav.php"); ?>
    <div id="container">
        <div class="header">
            <h1>搜索结果: <span><?php echo $text; ?></span></h1>
        </div>

        <div id="main">
            <div class="selection">
                <a class="active" href="search-all.php">全部</a>
                <a href="search-activity.php">活动</a>
                <a href="search-person.php">找人</a>
                <div style="clear: left;"></div>
            </div>
            <div class="result">
                <h2>

                    <span>相关用户</span>
                    <span class="num">(<?php echo $user_number ; ?>个)</span>
                    <a href="search-person.php">查看全部</a>
                    <br style="clear:both" />
                </h2>
                <div class="result-body">
                <?php
                if($user_number<3){
                    $num=$user_number;

                }else{
                    $num = 3;
                }
                for($i = 0;$i<$num;$i++){
                    $row = mysqli_fetch_array($result1);
                    $nickname = $row['nickname'];
                    $academy = $row['academy'];
                    $user_id = $row['user_id'];

                    $photo = $row['icon'];
                    $portrait=UPLOAD_PORTRAIT_FRONT_TO_BACK.$photo;//头像
                ?>
                    <div class="user-display">
                        <a class="pic" href="personal-page.php?id=<?php echo $user_id?>"><img src="<?php echo $portrait ; ?>" alt="用户头像" /></a>
                        <div class="user-info">
                            <a class="name" href="personal-page.php?id=<?php echo $user_id?>">  <?php echo $nickname; ?></a>
                            <br />
                            <br />
                            <p><?php echo $academy; ?></p>
                        </div>
                        <div style="clear: left;"></div>
                        <a class="add" href="#">关注此人</a>
                    </div>               
                <?php } ?>
                    <div style="clear: both"></div>
                </div>
                <h2>
                    <span>相关活动</span>
                    <span class="num">(<?php echo $activity_number; ?>条)</span>
                    <a href="search-activity.php">查看全部</a>
                    <br style="clear:both" />
                </h2>
                <?php
                if($activity_number<3){
                    $num=$activity_number;

                }else{
                    $num = 3;
                }
                for($i = 0;$i<$num;$i++){
                $row = mysqli_fetch_array($result2);
                $name = $row['name'];
                $activity_id = $row['activity_id'];
                $site = $row['site'];


                $time = $row['date'];
                $user_id = $row['user_id'];
                $user_name = $row['nickname'];
                $begin_time = $row['time_begin'];
                $end_time = $row['time_end'];
                $photo = $row['photo'];
                $image=UPLOAD_PATH_FRONT_TO_BACK.$photo;

                 $tag = $row['tag.name'];


                ?>

                <table>
                    <tbody>
                    <tr>
                        <td class="left">
                            <a href="activity.php?activity=<?php echo $activity_id?>"><img src="<?php echo $image ; ?>" alt="活动图片" /></a>
                        </td>
                        <td class="right">
                            <div class="content">
                                <h3><a href="activity.php?activity=<?php echo $activity_id?>"><?php echo $name; ?></a></h3>
                                <p class="detail">
                                    时间：<?php echo $time ;?> <?php echo date('H:i',strtotime($begin_time)); ?>-<?php echo date('H:i',strtotime($end_time)); ?><br />
                                    地点：<?php echo $site; ?><br />
                                    发起：<a href="personal-page.php?id=<?php echo $user_id?>"><?php echo $user_name;?></a>
                                </p>

                                <p class="tag">
                                    <?php
                                    //标签显示
                                    $query = "select t.name tag_name from activity a join activity_tag b join tag t where a.activity_id = b.activity_id and b.tag_id = t.tag_id and   a.activity_id=$activity_id";
                                    $data1 = mysqli_query($dbc,$query);
                                    $tag_number = mysqli_num_rows($data1);
                                    while($result4 = mysqli_fetch_array($data1))//标签
                                    {
                                    ?>
                                    <a><span><?php echo $result4['tag_name']; ?></span></a>


                                    <?php } ?>
                                </p>

                                <p class="info">
                                    <?php
                                    //查询活动喜欢人数
                                    $query = "select * from activity_love where activity_id = $activity_id";
                                    $data2 = mysqli_query($dbc,$query);
                                    $love_activity_count = mysqli_num_rows($data2);

                                    //查询活动参加人数
                                    $query = "select * from activity_join where activity_id = $activity_id";
                                    $data3 = mysqli_query($dbc,$query);
                                    $join_activity_count = mysqli_num_rows($data3);

                                    //查询评论条数人数

                                    $query = "select * from activity_comment where activity_id = $activity_id";
                                    $data4 = mysqli_query($dbc,$query);
                                    $comment_count = mysqli_num_rows($data4);

                                    ?>
                                    <span><b><?php echo $love_activity_count;?></b>人喜欢</span>
                                    <span><b><?php echo $join_activity_count;?></b>人要参加</span>
                                    <span><b><?php echo $comment_count;?></b>条评论</span>
                                </p>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
                <?php } ?>
                <div style="clear: right;"></div>
            </div>
        </div>
    </div>
<?php require_once("footer.php"); ?>

</body>
</html>