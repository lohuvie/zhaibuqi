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
    $photo_type = $_FILES['portrait']['type'];
    $photo_size = $_FILES['portrait']['size'];
    $photo= $user_id.time().".".substr($photo_type,6);       //上传portrait名字 user_id+time()+后缀名\

    if(isset($nickname)&&isset($email)&&isset($sex)){
        //判定图片类型
         if ((($photo_type == 'image/gif') || ($photo_type == 'image/jpeg') || ($photo_type == 'image/pjpeg') || ($photo_type == 'image/png'))
             && ($photo_size > 0)) {
           if ($_FILES['portrait']['error'] == 0) {
                // 放进目标文件夹
                $target = UPLOADPORTRAIT.$photo;
                if (move_uploaded_file($_FILES['portrait']['tmp_name'], $target)) {
                    $dbc = mysqli_connect(host,user,password,database);
                    $query = "select * from portrait";
                    $data = mysqli_query($dbc,$query)
                        or die('fuck');
                   //如果还没有头像
                    if(mysqli_num_rows($data) == 0){
                        $query = "insert into portrait(user_id,icon) values($user_id,'$photo')";
                        $data = mysqli_query($dbc,$query);
                    }else{
                        $result = mysqli_fetch_array($data);
                        $portrait_old = $result['icon'];
                        $query = "update portrait set icon = '$photo' where user_id = $user_id";
                        $data = mysqli_query($dbc,$query);
                        //删除原始图片
                        @unlink(UPLOADPORTRAIT.$portrait_old);
                    }
                     echo $query;

                }else {
                    echo '<p class="error">Sorry, there was a problem uploading your activity.</p>';
                }
            }
         }else {
             echo '<p class="error">文件类型必须是jpg，GIF，PNG并且大小大于0KB</p>';
         }
        $dbc = mysqli_connect(host,user,password,database);
        $query = "update user set nickname = '$nickname',email = '$email',sex = $sex,academy = '$academy',intro = '$intro' where user_id = '$user_id'";
        $data = mysqli_query($dbc,$query)
            or die('fuck');

        // Try to delete the temporary screen shot image file
        @unlink($_FILES['portrait']['tmp_name']);

       $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/personal-info.php';
       header('Location: ' . $home_url);
    }else {
        echo '<p class="error">Please enter all of the information to add your activities.</p>';
    }


    mysqli_close($dbc);
    //跳转到个人设置页


?>
