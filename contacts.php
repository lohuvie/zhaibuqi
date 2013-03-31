<?php
require_once("php/start-session.php");
require_once("php/util.php");

$personal_id = $_GET['id'];
$user_id = 0;
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
if(mysqli_num_rows($result) != 0){
    $data = mysqli_fetch_array($result,MYSQLI_BOTH);
    $name = $data['nickname'];
    //查询关注的人数量
    $attention_lists_count = 0;
    $query = "select status
                    from attention_fan af
                    where af.fan_id = $personal_id or (af.attention_id = $personal_id and af.status = 2) ";
    $result = mysqli_query($dbc,$query);
    if(mysqli_num_rows($result) != 0){
        $attention_lists_count = mysqli_num_rows($result);
    }
    //查询关注的人
    $query = "select af.attention_fan_id af_id,af.attention_id id_1,u1.nickname name_1,p1.icon icon_1,u1.academy academy_1,
                      af.fan_id id_2,u2.nickname name_2,p2.icon icon_2,u2.academy academy_2,
                      ag.attention_groups_id ag_id,g.name group_name,af.status status
                from portrait p1
                right join user u1 on u1.user_id = p1.user_id
                right join attention_fan af on af.attention_id = u1.user_id
                left join user u2 on af.fan_id = u2.user_id
                left join portrait p2 on u2.user_id = p2.user_id
				left join attention_groups ag on ag.attention_fan_id = af.attention_fan_id
				left join groups g on ag.groups_id = g.groups_id
                where af.fan_id = $personal_id or (af.attention_id = $personal_id and af.status = 2) order by af.attention_fan_id desc limit 12";
    $result = mysqli_query($dbc,$query);
    $attention_lists = Array();
    $attention_lists_index = 0;
    if(mysqli_num_rows($result) != 0){
        while($data = mysqli_fetch_array($result,MYSQLI_BOTH)){
            //用户关注列表
            if($data['id_1'] == $personal_id &&  $data['status'] == ATTENTION_EACH_OTHER)
                $attention_lists[$attention_lists_index] =array ('af_id'=>$data['af_id'],'id'=>$data['id_2'],'name'=>$data['name_2'],'portrait'=>$data['icon_2'],'academy'=>$data['academy_2'],'ag_id'=>$data['ag_id'],'group'=>$data['group_name']);
            else
                $attention_lists[$attention_lists_index] =array ('af_id'=>$data['af_id'],'id'=>$data['id_1'],'name'=>$data['name_1'],'portrait'=>$data['icon_1'],'academy'=>$data['academy_1'],'ag_id'=>$data['ag_id'],'group'=>$data['group_name']);
            $attention_lists_index++;
        }
    }
    //查询被关注的人的数量
    $fan_id_count = 0;
    $query = "select af.status
                    from portrait p1
                    right join user u1 on u1.user_id = p1.user_id
                    right join attention_fan af on af.fan_id = u1.user_id
                    left join user u2 on af.attention_id = u2.user_id
                    left join portrait p2 on u2.user_id = p2.user_id
                    where af.attention_id = $personal_id or (af.fan_id = $personal_id and af.status = 2)";
    $result = mysqli_query($dbc,$query);
    mysqli_num_rows($result);
    if(mysqli_num_rows($result) != 0){
        $fan_id_count = mysqli_num_rows($result);
    }

    //查询分组
    $groups = Array();
    $groups_count = 0;
    if($is_user){
        //显示分组
        $query = "select g.groups_id g_id,g.name g_name from user u
                    left join groups g on u.user_id = g.founder_id
                    where u.user_id = $personal_id";
        $result = mysqli_query($dbc,$query);
        if(mysqli_num_rows($result) != 0){
            while($data = mysqli_fetch_array($result,MYSQLI_BOTH)){
                $groups[$groups_count] = array ('id'=>$data['g_id'],'name'=>$data['g_name']);
                $groups_count++;
            }
        }
    }

}else{
    //用户不存在 跳转至404页面
    $url = 'http://' . $_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF']).'/404.php';
    header('Location: ' . $url);
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
            <h1><?php if($is_user){echo '我';}else{echo $name;}?>关注的人(<span><?php echo $attention_lists_count;?>个</span>)</h1>
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
                            <li class="passive" id="-1">全部</li>
                            <?php
                            //循环显示组
                            foreach($groups as &$g){
                                echo '<li id="'.$g['id'].'">'.$g['name'].'</li>';
                            }
                            unset($g);
                            ?>
                            <li id="0">未分组</li>
                        </ul>
                        <em id="manage-group">管理分组..</em>
                    </div>
                </div>
                <div class="operation move">
                    <p>移动至<span id="move"></span></p>
                    <div class="group-selection">
                        <ul>
                            <li class="passive" id="-1">全部</li>
                            <?php
                            //循环显示 移动至组
                            foreach($groups as &$g){
                                echo '<li value="'.$g['id'].'">'.$g['name'].'</li>';
                            }
                            unset($g);
                            ?>
                            <li id="0">未分组</li>
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
        <?php
        //循环显示关注的人
        foreach($attention_lists as &$al){
            ?>
            <div class="user-display" value="<?php echo $al['af_id'];?>">
                <a class="pic" href="personal-page.php?id=<?php echo $al['id'];?>"><img src="upload-portrait/<?php echo $al['portrait'];?>" alt="<?php echo $al['name'];?>" /></a>
                <div class="user-info">
                    <p><a class="name" href="personal-page.php?id=<?php echo $al['id'];?>"><?php echo $al['name'];?></a></p>
                    <p><?php echo isset($al['academy'])?$al['academy']:'';?></p>
                </div>
                <div style="clear: left;"></div>
                <?php if($is_user){
                    //显示组别 和 attention_group号
                    if(isset($al['group'])){
                        echo '<p class="extra" group="'.$al['ag_id'].'">组别:'.$al['group'].'</p>';
                    }else{
                        echo '<p class="extra" group="0">组别：未分组</p>';
                    }
                }
                ?>
            </div>
        <?php
        }
        unset($al);
        ?>
            <div class="clear">点击加载更多</div>
        </div>
        <b id="to-top"></b>
    </div>
    <?php require_once("footer.php"); ?>
    <script src="js/jquery-1.7.1.min.js"></script>
    <script src="js/apprise-v2.min/apprise-v2.min.js"></script>
    <script src="js/jquery.contacts.js"></script>
</body>
</html>