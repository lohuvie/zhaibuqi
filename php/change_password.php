<?php
    require_once('start-session.php');
    require_once('util.php');
    $dbc = mysqli_connect(host,user,password,database)
        or die ('Error connecting to mysql server');

    $email = $_SESSION['email'];
    $user_id =  $_SESSION['user_id'];        
    $passwd1 = $_REQUEST['new-password'];
    $passwd2 = $_REQUEST['password-confirmation'];

    /* RECEIVE VALUE */
    $currentValue = $_REQUEST['current-password'];

    $validateError = "The current-password is not correct";
    $validateSuccess = "The current-password is correct";

    /* RETURN VALUE */
    $arrayToJs = array();
    $arrayToJs[0] = array();
    $arrayToJs[0][0] = 'current-password';

    //判定当前密码是否正确
    $query = "SELECT * FROM user WHERE  user_id = '$user_id ' AND password = SHA('$currentValue')";
    $data = mysqli_query($dbc, $query)
        or die('Error querying database1');
    
    if(mysqli_num_rows($data) == 1){    //当前密码正确


        $arrayToJs[0][1] = true;                // RETURN TRUE
        $arrayToJs[0][2] = "密码修改成功!";
        echo json_encode($arrayToJs);
        // RETURN ARRAY WITH success
        
        //修改密码
        $query2 = "UPDATE user SET password = SHA('$passwd1') WHERE user_id = $user_id";
        $result = mysqli_query($dbc,$query2)
            or die('Error querying database2'); 
        
        /*
        //修改密码成功后自动登录
        $query3 = "SELECT user_id, email FROM user WHERE email = '$email' AND password = SHA('$passwd1')";
        $data = mysqli_query($dbc, $query3)
            or die('Error query');

        // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the personal-setting page
        $row = mysqli_fetch_array($data);
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['email'] = $row['email'];
        setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
        setcookie('email', $row['email'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
        
         
        //跳转到个人设置页面
        $setting_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/index.php';
        header('Location: ' . $setting_url);
        */

    }else{      // 当前密码错误
        $arrayToJs[0][1] = false;               // RETURN FALSE
        $arrayToJs[0][2] = "当前密码错误";
        echo json_encode($arrayToJs);
    }
    
    mysqli_close($dbc);

?>