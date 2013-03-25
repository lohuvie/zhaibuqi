<?php
require_once("php/start-session.php");
require_once("php/util.php");

$personal_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = USER_NO_LOGIN;
}

$is_user = false;
//判定当前用户是否为主用户
if($personal_id == $user_id){
    $is_user = true;
}else{
    $is_user = false;
}

$dbc = mysqli_connect(host,user,password,database);
//查询用户
$query = "select nickname from user where user_id = $personal_id";
$result = mysqli_query($dbc,$query);
if(!empty($result)){
    $data = mysqli_fetch_array($result,MYSQLI_BOTH);
    $name = $data['nickname'];

    //查询关注的人
    $query = "select af.attention_id id_1,af.fan_id id_2,u1.nickname name_1,p1.icon icon_1,u2.nickname name_2,p2.icon icon_2
                from portrait p1
                right join user u1 on u1.user_id = p1.user_id
                right join attention_fan af on af.attention_id = u1.user_id
                left join user u2 on af.fan_id = u2.user_id
                left join portrait p2 on u2.user_id = p2.user_id
                where af.fan_id = $personal_id or (af.attention_id = $personal_id and af.status = 2) limit 9";
    $result = mysqli_query($dbc,$query);
    $attention_ids = Array();
    $attention_id_count = 0;
    if(!empty($result)){
        while($data = mysqli_fetch_array($result,MYSQLI_BOTH)){
            //用户关注列表
            if($data['id_1'] == $personal_id &&  $data['status'] == ATTENTION_EACH_OTHER)
                $attention_ids[$attention_ids_count] =array ('id'=>$data['id_2'],'name'=>$data['name_2'],'portrait'=>$data['icon_2']);
            else
                $attention_ids[$attention_ids_count] =array ('id'=>$data['id_1'],'name'=>$data['name_1'],'portrait'=>$data['icon_1']);
            $attention_id_count++;
        }
    }
    //查询被关注的人的人数
    $query = "select af.fan_id id_1,af.attention_id id_2,u1.nickname name_1,p1.icon icon_1,u2.nickname name_2,p2.icon icon_2
                from portrait p1
                right join user u1 on u1.user_id = p1.user_id
                right join attention_fan af on af.fan_id = u1.user_id
                left join user u2 on af.attention_id = u2.user_id
                left join portrait p2 on u2.user_id = p2.user_id
                where af.attention_id = $personal_id or (af.fan_id = $personal_id and af.status = 2) limit 5";
    $result = mysqli_query($dbc,$query);
    $fan_id_count = 0;
    if(!empty($result)){
        $fan_id_count = mysqli_num_rows($result);
    }

}else{
    //用户不存在 跳转至404页面

}
mysqli_close($dbc);


?>
<!DOCTYPE html>
<html>
<head>
    <title>宅不起 | 好友管理</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="css/top-nav.css" type="text/css" rel="stylesheet" />
    <link href="css/footer.css" type="text/css" rel="stylesheet"/>
    <link href="css/contacts.css" type="text/css" rel="stylesheet"/>
    <link href="js/apprise-v2.min/apprise-v2.min.css" type="text/css" rel="stylesheet"/>
</head>
<body>
    <?php require_once("top-nav.php"); ?>
    <div id="container">
        <div id="dustbin"></div>
        <div class="fixed">
            <h1><?php if($is_user){echo '我';}else{echo $name;}?>关注的人(<span><?php echo $attention_id_count;?>个</span>)</h1>
            <p id="tips"></p>
            <div id="operation-box">
                <form action="" method="get">
                    <div>
                        <input type="text" name="friends-search" id="friends-search"/>
                        <button id="friends-search-btn" type="submit"><span></span></button>
                    </div>
                </form>
                <?php if($is_user){?>
                <div class="operation group">
                    <p>选择分组<span id="group"></span></p>
                    <div class="group-selection">
                        <ul>
                            <li class="passive">分组1</li>
                            <li>分组2</li>
                            <li>分组1</li>
                            <li>分组2</li>
                            <li>分组1</li>
                            <li>分组2</li>
                        </ul>
                        <em id="new-group">新建分组..</em>
                    </div>
                </div>
                <div class="operation move">
                    <p>移动至<span id="move"></span></p>
                    <div class="group-selection">
                        <ul>
                            <li class="passive">分组1</li>
                            <li>分组2</li>
                            <li>分组1</li>
                            <li>分组2</li>
                            <li>分组1</li>
                            <li>分组2</li>
                        </ul>
                    </div>
                </div>
                <div class="operation remove">
                    <p>删除<span id="remove"></span></p>
                </div>
                <?php }?>
            </div>
            <div id="sidebar">
                <a href="r-contacts.html">关注<?php if($is_user){echo '我';}else{echo $name;}?>的人(<span><?php echo $fan_id_count;?></span>)</a>
                <div class="tips">
                    <h3>操作提示</h3>
                    <p>
                        1.单击选项框选中目标，单击选择需要的操作
                    </p>
                    <p>
                        2.单击名字或头像可查看用户主页
                    </p>
                    <p>
                       3.在“选择分组”下拉菜单中双击分组可对分组进行重命名
                    </p>
                    <p>
                       4.撤销只能撤销最近一次与好友管理相关的操作
                    </p>
                </div>
            </div>
        </div>
        <div id="contacts-body">
            <div class="user-display" value="abc.com">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display" value="abc.com">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display" value="abc.com">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display" value="abc.com">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display" value="abc.com">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display" value="abc.com">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display" value="abc.com">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display" value="abc.com">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display" value="abc.com">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display" value="abc.com">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display" value="abc.com">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display" value="abc.com">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display" value="abc.com">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display" value="abc.com">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display" value="abc.com">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="user-display">
                <a class="pic" href="#"><img src="images/search-head.jpg" alt="" /></a>
                <div class="user-info">
                    <a class="name" href="#">李文超</a>
                    <br />
                    <br />
                    <p>华西基础医学与法医学院</p>
                </div>
                <div style="clear: left;"></div>
                <p class="extra">组别:</p>
            </div>
            <div class="clear-left"></div>
            <b id="loading">正在加载..</b>
        </div>
        <b id="to-top"></b>
    </div>
    <?php require_once("footer.php"); ?>
    <script src="js/jquery-1.7.1.min.js"></script>
    <script src="js/apprise-v2.min/apprise-v2.min.js"></script>
    <script src="js/jquery.contacts.js"></script>
</body>
</html>