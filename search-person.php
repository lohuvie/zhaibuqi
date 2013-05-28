<?php
$text = $_COOKIE['search_person'];

require_once('php/util.php');
$dbc = mysqli_connect(host,user,password,database);
$query = "select *
    from user left join  portrait on(user.user_id=portrait.user_id)
    where nickname LIKE '%$text%' ";//包含搜索框的内容(名字里面)
$result1 = mysqli_query($dbc,$query);

$user_number = mysqli_num_rows($result1);//结果条数
$cur_page = isset($_GET['page'])?$_GET['page']:1;//获得当前的页面没有则设置
$per_page = 9;//每个页面显示9条
$skip = (($cur_page-1)*$per_page);
$num_pages=ceil($user_number/$per_page);//页面数,向上取整

$query = $query."LIMIT $skip,$per_page ";//包含搜索框的内容(名字里面)
$result2 = mysqli_query($dbc,$query);


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
?><!DOCTYPE html>
<html>
<head>
    <title>宅不起 | 搜索结果</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="css/search-person.css" type="text/css" rel="stylesheet" />
    <link href="css/top-nav.css" type="text/css" rel="stylesheet" />
    <link href="css/footer.css" type="text/css" rel="stylesheet"/>
</head>
<body>
    <?php require_once("top-nav.php"); ?>
    <div id="container">
        <div class="header">
            <h1>搜索结果: <span><?php echo $text; ?></span></h1>
        </div>
        <div id="main">
            <div class="selection">
                <a href="search-all.php">全部</a>
                <a href="search-activity.php">活动</a>
                <a class="active" href="search-person.php">找人</a>
                <div style="clear: left;"></div>
            </div>
            <div class="result">
                <div class="result-body">
            <?php
           while($row=mysqli_fetch_array($result2)){//循环显示用户

            $nickname = $row['nickname'];
            $academy = $row['academy'];
            $user_id = $row['user_id'];

            $photo = $row['icon'];
            $portrait=UPLOAD_PORTRAIT_FRONT_TO_BACK.$photo;//头像
            ?>
                    <div class="user-display">
                        <a class="pic" href="personal-page.php?id=<?php echo $user_id?>"><img src="<?php echo $portrait ; ?>" 
                            alt="用户头像" /></a>
                        <div class="user-info">
                            <a class="name" href="personal-page.php?id=<?php echo $user_id?>"> <?php echo $nickname; ?></a>
                            <br />
                            <br />
                            <p><?php echo $academy; ?></p>
                        </div>
                        <div style="clear: left;"></div>
                        <a class="add" href="#">关注此人</a>
                    </div>
                <?php } ?>
                </div>
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