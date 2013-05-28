<?php
require_once "php/start-session.php";
require_once"php/util.php";
$dbc = mysqli_connect(host,user,password,database);
$query2="select name,a.activity_id ,count(a.user_id) from activity_love a join activity b on a.activity_id=b.activity_id  group by activity_id order by count(a.user_id) desc ";
$result2 = mysqli_query($dbc,$query2);
$query1 ="select name,a.activity_id ,count(a.user_id) from activity_join a join activity b on a.activity_id=b.activity_id  group by activity_id order by count(a.user_id) desc  ";
$result1 = mysqli_query($dbc,$query1);

?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="Keywords" content="宅不起 大学生 活动 推送" />
    <meta name="description" content="宅不起 大学生 活动 推送" />

	<title>宅不起 | 首页</title>

    <link href="css/top-nav.css" type="text/css" rel="stylesheet" />
    <link href="css/index.css" type="text/css" rel="stylesheet" />
    <link href="css/footer.css" type="text/css" rel="stylesheet" />

	<!--[if IE]>
	<style type="text/css"> 
	/* 请将所有版本的 IE 的 css 修复放在这个条件注释中 */
	</style>
	<![endif]-->
</head>

<body>
    <?php require_once("top-nav.php"); ?>
	<div id="container">
			<div id="header">
				<div id="header-nav">
					<ul>
                        <li>
                            <a href="#">全部活动</a>
                        </li>
						<li>
							<a href="#">出去耍</a>
						</li>
						<li>
							<a href="#">社团活动</a>
						</li>
						<li>
							<a href="#">比赛</a>
						</li>
						<li>
							<a href="#">讲座</a>
						</li>
					</ul>
                    <div class="create-new">
                        <?php if(!isset($_SESSION['user_id'])){?>
                            <a href="login.php" title="新建" class="create-btn">
                        <?php }else{ ?>
                            <a href="new-activity.php" title="新建" class="create-btn">
                        <?php }?>
                            &nbsp;
                        </a>
                    </div>
				</div>
			</div>
		<div id="sidebar">
			<div class="board">
                <h3>最多人喜欢 Top5</h3>
			    <ol>
                    <?php for($i=0;$i<5;$i++){
                        $data = mysqli_fetch_array($result2);
                        $nickname =$data['name'];
                        $activity_id=$data['activity_id'];


                        ?>

                    <li><a href="activity.php?activity=<?php echo $activity_id;?>"><?php echo $nickname;?></a></li>
                   <?php } ?>
                </ol>
            </div>
            <div class="board">
                <h3>最多人参加 Top5</h3>
                <ol>
                    <?php for($i=0;$i<5;$i++){
                    $data1 = mysqli_fetch_array($result1);
                    $nickname =$data1['name'];
                    $activity_id=$data1['activity_id'];
                    ?>
                        <li><a href="activity.php?activity=<?php echo $activity_id;?>"><?php echo $nickname;?></a></li>
                    <?php } ?>
                </ol>
            </div>
		</div>
		<!-- end #sidebar -->
		<div id="waterfall">
	    	<!--<div class="single-activity" >
                <a class="activity-pic" href=""><img src="images/test.png" alt="图片示例235*350" /></a>
                <a class="activity-title" href="">活动标题</a><br/>
                <p>时间：</p>
                <p>地点：</p>
                <p>活动介绍简介:</p>
            </div>
            <div class="single-activity" >
                <a class="activity-title" href="">活动标题</a><br/>
                <p>时间：</p>
                <p>地点：</p>
                <p>活动介绍简介:</p>
            </div>
            <div class="single-activity" >
                <a class="activity-pic" href=""><img src="images/test.png" alt="图片示例235*350" /></a>
                <a class="activity-title" href="">活动标题</a><br/>
                <p>时间：</p>
                <p>地点：</p>
                <p>活动介绍简介:</p>
            </div>
            <div class="single-activity" >
                <a class="activity-title" href="">活动标题</a><br/>
                <p>时间：</p>
                <p>地点：</p>
                <p>活动介绍简介:</p>
            </div>
            <div class="single-activity" >
                <a class="activity-pic" href=""><img src="images/test.png" alt="图片示例235*350" /></a>
                <a class="activity-title" href="">活动标题</a><br/>
                <p>时间：</p>
                <p>地点：</p>
                <p>活动介绍简介:</p>
            </div>
            <div class="single-activity" >
                <a class="activity-title" href="">活动标题</a><br/>
                <p>时间：</p>
                <p>地点：</p>
                <p>活动介绍简介:</p>
            </div> -->
		</div>
		<!-- end #Waterfall -->
        <p id="add-tips">正在加载更多活动...</p>
        <b id="to-top"></b>
	</div>
	<!-- end #container -->
    <?php require_once("footer.php"); ?>
    <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.masonry.min.js"></script>
    <script type="text/javascript" src="js/waterfall.js"></script>
    <script type="text/javascript" src="js/jquery.index.js"></script>
</body>
</html>
