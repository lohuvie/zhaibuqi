<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>信息显示</title>
</head>
<?php
    require_once("start-session.php");
    require_once("util.php");
    $user_id = $_SESSION['user_id'];
    $nickname=$_POST['name'];
    $email=$_POST['email'];
    $sex = $_POST['gender'];
    $academy = $_POST['institude'];

    $intro = $_POST['brief-intro'];
echo "'sex'.'$sex'.'nickname'.'$nickname'.'email'.'$email'.'</br>'";


    $photo= $_SESSION['portrait_name'];      //上传portrait名字 user_id+time()+后缀名\


// 切图。。。
//获取图片百分比
if(!empty($_POST['size'])){
$coordinate_line = $_POST['pos'];
$coordinate_line1 = explode(",",$coordinate_line);
$coordinate = $_POST['size'];
$coordinate_line2 = explode(",",$coordinate);

//获取图片坐标
$image_size   =   getimagesize(UPLOAD_PORTRAIT.$photo);
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
$uploadBanner =UPLOAD_PORTRAIT.$photo;
$sliceBanner = UPLOAD_PORTRAIT. $cut_name;//剪切后的图片存放的位置

//创建图片


$dst_pic = imagecreatetruecolor($w, $z);    //  目标图像
$src_pic = getImageHander($uploadBanner);     //        源图像
imagecopyresampled($dst_pic, $src_pic, 0, 0,$x,$y,$w,$z,$w,$z);
imagejpeg($dst_pic, $sliceBanner);
imagedestroy($src_pic);
imagedestroy($dst_pic);
}
//    //返回新图片的位置
//    echo  $sliceBanner;
//}

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
//}

    if(!empty($nickname)&&!empty($email)&&isset($sex)){
        echo "dasdasdasdasdasdasd";

                    $dbc = mysqli_connect(host,user,password,database);
                    $query = "select * from portrait where user_id = '$user_id'";
                    $data = mysqli_query($dbc,$query)
                        or die('fuck');
        if(isset($coordinate)){
                   //如果还没有头像
                    if(mysqli_num_rows($data) == 0){
                        $query = "insert into portrait(user_id,icon) values($user_id,'$cut_name')";
                        $data = mysqli_query($dbc,$query);
                    }else{
                        $result = mysqli_fetch_array($data);
                        $portrait_old = $result['icon'];
                        $query = "update portrait set icon = '$cut_name' where user_id = $user_id";
                        $data = mysqli_query($dbc,$query);

                    }
        }
                     echo $query;




        $query = "update user set nickname = '$nickname',email = '$email',sex = $sex,academy = '$academy',intro = '$intro' where user_id = '$user_id'";
        $data = mysqli_query($dbc,$query)
            or die('fuck');


       $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/personal-info.php';
       header('Location: ' . $home_url);
    }else {
        echo '<p class="error">Please enter all of the information to add your activities.</p>';
    }


    mysqli_close($dbc);
    //跳转到个人设置页


?>
