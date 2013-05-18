<?php
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

require_once("start-session.php");
require_once("util.php");
if(isset($_SESSION['user_id'])){
$user_id = $_SESSION['user_id'];
$nickname=$_POST['name'];
$email=$_POST['email'];
$sex = $_POST['gender'];
$academy = $_POST['institude'];
$intro = $_POST['brief-intro'];
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
    $cut_name = 'cut_'. $photo;
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
    if(!empty($nickname)&&!empty($email)&&isset($sex)){
        $dbc = mysqli_connect(host,user,password,database);
        $query = "select * from portrait where user_id = '$user_id'";
        $data = mysqli_query($dbc,$query)or die('fuck');
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
        $query = "update user set nickname = '$nickname',email = '$email',sex = $sex,academy = '$academy',intro = '$intro' where user_id = '$user_id'";
        $data = mysqli_query($dbc,$query)
            or die('fuck');


       $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/personal-info.php';
       header('Location: ' . $home_url);
    }else {
        echo '<p class="error">Please enter all of the information to add your activities.</p>';
    }
    mysqli_close($dbc);

}else{
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/login.php';
    header('Location: ' . $home_url);
}
