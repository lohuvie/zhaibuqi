<?php

//关注的人
require_once("../php/util.php");
require_once("../vo/Attention.php");
require_once("../vo/AttentionFunList.php");

header('Content-type: text/json');
header('Content-type: application/json');

$personal_id = $_GET['id'];
$page = $_GET['page'];
$group = $_GET['group'];


$page *= 12;
$group_str = "";
//判定type
if($group == -1){
    //全部类别
}else if($group == 0){
    //未分类
    $group_str = "and g.groups_id is null";
}else{
    //其他
    $group_str = "and g.groups_id=".$group;
}

//连接数据库
$dbc = mysqli_connect(host,user,password,database);
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
                where (af.fan_id = $personal_id or (af.attention_id = $personal_id and af.status = 2)) ".$group_str." order by af.attention_fan_id desc limit $page,12";
$result = mysqli_query($dbc,$query);

$attention_list = new AttentionFunList();
$attention_list_index = 0;
while($data = mysqli_fetch_array($result,MYSQLI_BOTH)){
    //用户关注列表
    $attention = new Attention();

    $attention->setAfId($data['af_id']);
    $attention->setStatus($data['status']);
    $attention->setAgId($data['ag_id']);
    if(isset($data['group_name']))
        $attention->setGroup(($data['group_name']));

    if($data['id_1'] == $personal_id &&  $data['status'] == ATTENTION_EACH_OTHER){
        $attention->setId($data['id_2']);
        $attention->setName($data['name_2']);
        $attention->setPotrait(UPLOAD_PORTRAIT_FRONT_TO_BACK.$data['icon_2']);
        $attention->setAcademy($data['academy_2']);
    }else{
        $attention->setId($data['id_1']);
        $attention->setName($data['name_1']);
        $attention->setPotrait(UPLOAD_PORTRAIT_FRONT_TO_BACK.$data['icon_1']);
        $attention->setAcademy($data['academy_1']);
    }
    $attention->setHref("personal-page.php?id=".$attention->getId());
    //清空NULL值
    foreach($attention as $key => $value){
        if(is_null($value))
            $attention->$key = '';
    }

    //将该关注的人添加至列表
    $attention_list->setPerson($attention,$attention_list_index);
    $attention_list_index++;
}
//设置列表是否为结束
if($attention_list_index==12)
    $attention_list->setEnd('');

//返回json
$s = json_encode($attention_list);
echo $s;
mysqli_close($dbc);
/*
 * {
    "person":[
        {
            "portrait":"images/search-head.jpg",
            "href":"a",
            "name":"name",
            "institude":"institude",
            "group":"group",
            "email":"email"
        },
        {
            "portrait":"images/search-head.jpg",
            "href":"a",
            "name":"name",
            "institude":"institude",
            "group":"group",
            "email":"email"
        },
        {
            "portrait":"images/search-head.jpg",
            "href":"a",
            "name":"name",
            "institude":"institude",
            "group":"group",
            "email":"email"
        },
        {
            "portrait":"images/search-head.jpg",
            "href":"a",
            "name":"name",
            "institude":"institude",
            "group":"group",
            "email":"email"
        }
    ],
    "end":"end"
}

 */


