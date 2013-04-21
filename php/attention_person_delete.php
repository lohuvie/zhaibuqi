<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lohuvie
 * Date: 13-4-14
 * Time: 下午4:21
 * To change this template use File | Settings | File Templates.
 */
require_once("start-session.php");
require_once("util.php");
header('Content-type: text/json');
header('Content-type: application/json');
define('REQUEST_ERROR',-3);
define('SUCCESS',0);

$persons = $_POST['person'];
$obj = json_decode($persons);

$user_id = $_SESSION['user_id'];
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = USER_NO_LOGIN;
}

$dbc = mysqli_connect(host,user,password,database);
$query = "";
if($user_id != USER_NO_LOGIN){
    try{
        //返回json数据
        $arr = array('attention_status'=>SUCCESS);
        for($i=0;$i<count($obj);$i++){
            $personal_id = $obj[$i]->{'personal_id'};
            $attention_fan_id = $obj[$i]->{'personValue'};
            $attention_status = $obj[$i]->{'status'};

            if($attention_status == ATTENTION_ONLY){

                //用户以前已关注 删除关注关系
                $query = "delete from attention_fan where attention_fan_id = $attention_fan_id";
                $result = mysqli_query($dbc,$query);

            }else if($attention_status == ATTENTION_EACH_OTHER){
                //用户间以前为互相关注关系 更改为被关注关系
                $query = "update attention_fan
                    set attention_id = $user_id,fan_id = $personal_id,status = ".ATTENTION_ONLY."
                    where attention_fan_id = $attention_fan_id";
                $result = mysqli_query($dbc,$query);

            }else{
                //请求值错误
                $arr = array('attention_status'=>REQUEST_ERROR);
                break;
            }
        }
    }catch (Exception $e){
        //返回数据库错误
        $arr = array('attention_status'=>DATABASE_ERROR);
    }
}else{
    //返回用户未登陆
    $arr = array('attention_status'=>USER_NO_LOGIN);
}

echo json_encode($arr);
mysqli_close($dbc);