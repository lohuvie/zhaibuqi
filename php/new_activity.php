<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>信息提示</title>
</head>
<?php
    require_once('util.php');
    require_once('start-session.php');
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];


    $title = $_POST['title'];
    $category = $_POST['category'];
    $date = $_POST['date'];
    $time_begin = $_POST['time-begin'];
    $time_end = $_POST['time-end'];
    $place = $_POST['place'];
    $introduction = $_POST['introduction'];
    $cost_class = $_POST['cost-class'];
    $authority_class = $_POST['authority-class'];
    $tags = $_POST['tags'];

    $photo= $_SESSION['photo_name'];      //上传海报名字 time()+后缀名\
    echo $photo.'gg';
    //开始时间结束时间判定
    if($time_begin == "开始时间"){
        $time_begin = "00:00:00";
    }
    if($time_end == "结束时间"){
        $time_end = "00:00:00";
    }
// 切图。。。
//获取图片百分比
$coordinate_line = $_POST['pos'];
$coordinate_line1 = explode(",",$coordinate_line);
$coordinate = $_POST['size'];
$coordinate_line2 = explode(",",$coordinate);

//获取图片坐标
$image_size   =   getimagesize(UPLOADPATH.$photo);
$width = $image_size['0'];
$height =$image_size['1'];
$y = $height*$coordinate_line1['0'];  // y坐标
$x = $width*$coordinate_line1['1'];   //    x坐标
$w =  $width*$coordinate_line2['0'];     //       宽度
$z =  $height*$coordinate_line2['1'];      //         高度
echo $width.'</br>'.$height.'</br>'.$x.'</br>'.$y.'</br>'.$w.'</br>'.$coordinate_line2['0'].'</br>'.$z;
//function sliceBanner(){
//    $x = (int)$coordinate_line1['1'];
//    $y = (int)$_POST['y'];
//    $w = (int)$_POST['w'];
//    $h = (int)$_POST['h'];

//剪切后小图片的名字
//    $pic = $_POST['poster'];
//    $str = explode(".",$pic);
//    $type = $str[1];
$cut_name = 'cut_'. $photo;
//$_SESSION['cut_name'] = $cut_name;
//    $cut_name = 'cut_'. $photo;
$uploadBanner =UPLOADPATH.$photo;
$sliceBanner = UPLOADPATH. $cut_name;//剪切后的图片存放的位置


//初始化图片
    function getImageHander ($url) {
        $size=@getimagesize($url);
        switch($size['mime']){
            case 'image/jpeg': $im = imagecreatefromjpeg($url);break;
            case 'image/gif' : $im = imagecreatefromgif($url);break;
            case 'image/png' : $im = imagecreatefrompng($url);break;
            default: $im=false;break;
        }

        return $im;
    }
//创建图片
$dst_pic = imagecreatetruecolor($w, $z);    //  目标图像
$src_pic = getImageHander($uploadBanner);     //        源图像
imagecopyresampled($dst_pic, $src_pic, 0, 0,$x,$y,$w,$z,$w,$z);
imagejpeg($dst_pic, $sliceBanner);
imagedestroy($src_pic);
imagedestroy($dst_pic);

//    //返回新图片的位置
//    echo  $sliceBanner;
//}



    if(!empty($title)&&!empty($date)&&!empty($time_begin)&&!empty($time_end)&&!empty($place)
        &&!empty($introduction)&& (isset($_SESSION['load_picture']))){
        //判定图片类型
//         if ((($photo_type == 'image/gif') || ($photo_type == 'image/jpeg') || ($photo_type == 'image/pjpeg') || ($photo_type == 'image/png'))
//             && ($photo_size > 0)) {
//            if ($_FILES['poster']['error'] == 0) {
//                // Move the file to the target upload folder
//                $target = UPLOADPATH.$photo;
//                if (move_uploaded_file($_FILES['poster']['tmp_name'], $target)) {
                    $dbc = mysqli_connect(host,user,password,database)
                        or die("error connect");

                    $register_time = date("y-m-d h:i:s",time());
                    //将活动信息载入activity表
                    $query="insert into activity values(null,$user_id,'$title','$category','$place','$introduction',$cost_class,'$register_time',0,$authority_class)";
                    echo $query;
        $result = mysqli_query($dbc,$query)
                        or die("error querying database");

                    //获取活动ID
                    $query = "select activity_id from activity where name = '$title' and type = '$category' and site = '$place' and activity_register_time = '$register_time'";
                    $result = mysqli_query($dbc, $query)
                        or die("error querying data");

                    $row = mysqli_fetch_array($result) or die("ddd");
                    $activity_id = $row['activity_id'];
                    //将活动信息载入activity-photo表
                    $query = "insert into activity_photo values(null,'$activity_id','$cut_name',1)";
                    $result = mysqli_query($dbc,$query)
                        or die("error querying database1");
                    //将活动信息载入activity-time表
                    $query="insert into activity_time values(null,'$activity_id','$date','$time_begin','$time_end')";
                    $result = mysqli_query($dbc,$query)
                        or die("error querying database2");

                    //将标签分割
                    $tagsArray = explode(',',$tags);
                    for($i = 0;$i<count($tagsArray);$i++){
                        $tag = $tagsArray[$i];
                        echo $tag;
                        try{
                            //存入标签
                            $query="insert into tag(name) values('$tag')";
                            $result = mysqli_query($dbc,$query);
                        }catch(Exception $e ){}
                        //查询标签id
                        $query = "select tag_id from tag where name = '$tag'";
                        $result = mysqli_query($dbc, $query)
                            or die("error querying tag");

                        $row = mysqli_fetch_array($result);
                        $tag_id = $row['tag_id'];
                        //存入标签-活动关系
                        try{
                        $query="insert into activity_tag values($activity_id,$tag_id)";
                        $result = mysqli_query($dbc,$query);
                        }catch (Exception $e){}
                    }

                    mysqli_close($dbc);

                    //新建活动成功后 跳转到活动页面（未审核）
                    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/activity.php?activity='.$activity_id;
                    header('Location: ' . $home_url);
                }
//                else {
//                    echo '<p class="error">Sorry, there was a problem uploading your activity.</p>';
//                }
//            }
//         }
//         else {
//             echo '<p class="error">文件类型必须是jpg，GIF，PNG并且大小大于0KB</p>';
//         }
//
//        // Try to delete the temporary screen shot image file
//        @unlink($_FILES['poster']['tmp_name']);
//
//    }
    else {
        echo '<p class="error">请填入所有信息.</p>';
    }
}else{
    //跳转至登陆页面
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/login.php';
    header('Location: ' . $home_url);
}


?>