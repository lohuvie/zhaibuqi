<?php
$text = $_COOKIE['search_activity'];

require_once('php/util.php');
$dbc = mysqli_connect(host,user,password,database);
//活动的处理



$query="select * from activity  a join activity_time b on a.activity_id = b.activity_id
    left  join user c on a.user_id = c.user_id
    left join activity_photo d on a.activity_id = d.activity_id
    where a.name like '%$text%' and approved =1";//多个表的链接
$result2 = mysqli_query($dbc,$query);
$activity_number = mysqli_num_rows($result2);//活动的查询

//$user_number = mysqli_num_rows($result1);//结果条数
$cur_page = isset($_GET['page'])?$_GET['page']:1;//获得当前的页面没有则设置
$per_page = 5;//每个页面显示5条
$skip = (($cur_page-1)*$per_page);
$num_pages=ceil($activity_number/$per_page);//页面数,向上取整

$query = $query." LIMIT $skip,$per_page ";//包含搜索框的内容(名字里面)
$result3 = mysqli_query($dbc,$query);


function generate_page_links( $cur_page, $num_pages) {
    $page_links = '';

    // If this page is not the first page, generate the "previous" link
    if ($cur_page > 1) {
        $page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?&page=' . ($cur_page - 1) . '">上一页</a> ';
    }

    // Loop through the pages generating the page number links
    for ($i = 1; $i <= $num_pages; $i++) {
        if ($cur_page == $i) {
            $page_links .= '<span class="active">' . $i. '</span>';
        }
        else {
            $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?&page=' . $i . '"> ' . $i . '</a>';
        }
    }

    // If this page is not the last page, generate the "next" link
    if ($cur_page < $num_pages) {
        $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?&page=' . ($cur_page + 1) . '">下一页</a>';
    }

    return $page_links;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>宅不起 | 搜索结果</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="css/search-activity.css" type="text/css" rel="stylesheet" />
    <link href="css/top-nav.css" type="text/css" rel="stylesheet" />
    <link href="css/footer.css" type="text/css" rel="stylesheet"/>
</head>
<body>
    <?php require_once("top-nav.php"); ?>
    <div id="container">
        <div class="header">
            <h1>搜索结果: <span><?php echo $text;?></span></h1>
        </div>

        <div id="main">
            <div class="selection">
                <a href="search-all.php">全部</a>
                <a class="active" href="search-activity.php">活动</a>
                <a href="search-person.php">找人</a>
                <div style="clear: left;"></div>
            </div>
            <div class="result">
                <table>
                    <tbody>            
            <?php
            while($row=mysqli_fetch_array($result3)){//循环显示活动
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
                        <tr>
                            <td class="left">
                                <a href="activity.php?activity=<?php echo $activity_id?>"><img src="<?php echo $image ; ?>" alt="活动图片" /></a>
                            </td>
                            <td class="right">
                                <div class="content">
                                    <h3><a href="#"><?php echo $name; ?></a></h3>
                                    <p class="detail">
                                        时间：<?php echo $time ;?> <?php echo date('H:i',strtotime($begin_time)); ?>-<?php echo date('H:i',strtotime($end_time)); ?><br />
                                        地点：<?php echo $site; ?><br />
                                        发起：<a href="personal-page.php?id=<?php echo $user_id?>"><?php echo $user_name;?></a>
                                    </p>
                                    <p class="tag">
                                        <?php
                                        //标签显示
                                        $query = "select t.name tag_name from activity a join activity_tag b join tag t where
                                        a.activity_id = b.activity_id and b.tag_id = t.tag_id and   a.activity_id=$activity_id";
                                        $data1 = mysqli_query($dbc,$query);
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


                <?php } ?>
                    </tbody>
                </table>
                <div class="page-nav">
                <?php
                if($num_pages>1){
                    echo generate_page_links($cur_page,$num_pages);
                }?>
                </div>
                <div style="clear: right;"></div>
            </div>
        </div>
    </div>
    <?php require_once("footer.php"); ?>
</body>
</html>