<?php

//关注的人
require_once("../php/util.php");
require_once("../vo/AttentionFunList.php");
require_once("../vo/Fan.php");

header('Content-type: text/json');
header('Content-type: application/json');

$personal_id = $_GET['id'];
$page = $_GET['page'];
$group = $_GET['group'];


$page *= 12;

$dbc = mysqli_connect(host,user,password,database);

//查询关注的人
$query = "select af.attention_fan_id af_id,af.attention_id id_1,u1.nickname name_1,p1.icon icon_1,u1.academy academy_1,
                af.fan_id id_2,u2.nickname name_2,p2.icon icon_2,u2.academy academy_2,af.status status
                from portrait p1
                right join user u1 on u1.user_id = p1.user_id
                right join attention_fan af on af.attention_id = u1.user_id
                left join user u2 on af.fan_id = u2.user_id
                left join portrait p2 on u2.user_id = p2.user_id
				left join attention_groups ag on ag.attention_fan_id = af.attention_fan_id
				left join groups g on ag.groups_id = g.groups_id
                where (af.attention_id = $personal_id or (af.fan_id = $personal_id and af.status = 2)) order by af.attention_fan_id desc limit ".$page.",12";
$result = mysqli_query($dbc,$query);
$fan_list_index = 0;
$fan_list = new AttentionFunList();


while($data = mysqli_fetch_array($result,MYSQLI_BOTH)){
    $fan = new Fan();
    //粉丝列表
    $fan->setAfId($data['af_id']);
    $fan->setStatus($data['status']);
    if($data['id_2'] == $personal_id &&  $data['status'] == ATTENTION_EACH_OTHER){
        $fan->setId($data['id_1']);
        $fan->setName($data['name_1']);
        $fan->setPortrait(UPLOAD_PORTRAIT_FRONT_TO_BACK.$data['name_1']);
        $fan->setAcademy($data['academy_1']);
    }else{
        $fan->setId($data['id_2']);
        $fan->setName($data['name_2']);
        $fan->setPortrait(UPLOAD_PORTRAIT_FRONT_TO_BACK.$data['name_2']);
        $fan->setAcademy($data['academy_2']);
    }
    $fan->setHref("personal-page.php?id=".$fan->getId());
    //清空null值
    foreach($fan as $key => $value){
        if(is_null($value))
            $fan->$key = '';
    }
    //将该粉丝添加至列表
    $fan_list -> setPerson($fan,$fan_list_index);
    $fan_list_index++;
}

//设置列表是否为结束
if($fan_list_index == 12)
    $fan_list->setEnd('');

//返回json数据
$str = json_encode($fan_list);
echo $str;
mysqli_close($dbc);
