<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>信息提示</title>
</head>
<?php
header("Content-Type:text/html;charset=utf-8");
require_once('util.php');
require_once('start-session.php');

$photo_type = $_FILES['portrait']['type'];
$photo_size = $_FILES['portrait']['size'];
echo $photo_type;
$photo= time().".".substr($photo_type,6);       //上传海报名字 time()+后缀名
$_SESSION['portrait_name']=$photo;


//判定图片类型
if ((($photo_type == 'image/gif') || ($photo_type == 'image/jpeg') || ($photo_type == 'image/pjpeg') || ($photo_type == 'image/png'))
    && ($photo_size > 0)&&($photo_size <2097152)) {
    if ($_FILES['portrait']['error'] == 0) {
        // Move the file to the target upload folder
        $target =UPLOAD_PORTRAIT.$photo;
        $target1=UPLOAD_PORTRAIT_FRONT_TO_BACK.$photo;

        move_uploaded_file($_FILES['portrait']['tmp_name'], $target) ;
        $_SESSION['load_portrait']=$target;

        echo " <p id='complete'>$target1<p> ";

    }
    else {
        echo '<p class="error">Sorry, there was a problem uploading your activity.</p>';
    }
}

else {
    echo '<p class="error">文件类型必须是jpg，GIF，PNG并且大小大于0KB小于2M</p>';
}

// Try to delete the temporary screen shot image file
@unlink($_FILES['portrait']['tmp_name']);


?>


