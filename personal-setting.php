<?php
require_once "php/start-session.php";
?>
<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>宅不起 | 设置</title>

    <!-- 在head标签内 引入Jquery -->
    <script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
    <!-- 在head标签内 引入Jquery 验证插件 -->
    <script src="js/jquery.validationEngine.js" type="text/javascript"></script>
    <script src="js/jquery.personal-setting.js" type="text/javascript"></script>

    <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" media="screen" title="no title" charset="utf-8" />
    <link type="text/css" rel="stylesheet" href="css/personal-setting.css"/>
    <link href="css/top-nav.css" type="text/css" rel="stylesheet" />
    <link href="css/footer.css" type="text/css" rel="stylesheet"/>
    <link href="css/setting-nav.css" type="text/css" rel="stylesheet"/>
</head>
<body>
    <?php require_once("top-nav.php"); ?>
    <div id="container">
        <!-- header -->
        <div class="header">
            <ul class="nav-tabs">
                <li>
                    <a href="personal-info.php">
                        基本信息
                    </a>
                </li>
                <li class="active">
                    <a href="#">
                        设置
                    </a>
                </li>
            </ul>
        </div>
        <!-- end header -->
        <div class="personal-setting" id="personal-setting">
            <div class="fields">
                <h3>修改密码</h3>
                <hr/>
                <form id="password-form" action="php/change_password.php" class="password-form" method="post">
                    <table>
                        <tbody>
                            <tr>
                                <td><label for="current-password">当前密码</label></td>
                                <td>
                                    <input id="current-password" name="current-password" type="password" 
                                    class="validate[required,minSize[6],maxSize[18]] text-input"/>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="new-password">新密码</label></td>
                                <td>
                                    <input id="new-password" name="new-password" type="password"
                                    class="validate[required,minSize[6],maxSize[18]] text-input" />
                                </td>
                            </tr>
                            <tr>
                                <td><label for="password-confirmation">确认密码</label></td>
                                <td>
                                    <input id="password-confirmation" name="password-confirmation" type="password" 
                                    class="validate[required,equals[new-password]] text-input" />
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <button type="submit" class="submit-btn" id="change-password" name="password-submit" >修改密码</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="fields">
                <h3>账号连接</h3>
                <hr/>
                <form action="" class="link-form" method="post">
                    <table>
                        <tbody>
                            <tr>
                                <td><label for="renren-link">与人人账号连接</label></td>
                                <td>
                                    <a id="renren-link" class="submit-btn">与人人账号连接</a>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="qq-link">与QQ账号连接</label></td>
                                <td>
                                    <a id="qq-link" class="submit-btn">与QQ账号连接</a>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="sina-link">与新浪微博连接</label></td>
                                <td>
                                    <a id="sina-link" class="submit-btn">与新浪微博连接</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="fields" id="mail-fiels">
                <h3>邮件设置</h3>
                <hr/>
                <p>通知我，若发生如下状况...</p>
                <form action="php/email_settings.php" class="mail-form" method="post">
                    <table>
                        <tbody>
                        <tr>
                            <td><label for="not-use-time">我没有登录</label></td>
                            <td id="not-use-time">
                                <input type="radio" id="three-days" name="not-use-time" value="3"/>
                                <label for="three-days">3天</label>
                                <input type="radio" id="seven-days" name="not-use-time" value="7" checked="checked"/>
                                <label for="seven-days">7天</label>
                                <input type="radio" id="never" name="not-use-time" value="0"/>
                                <label for="never">从不</label>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="message">有新消息时</label></td>
                            <td id="message">
                                <input type="radio" id="notice-yes" name="notice" value="yes" checked="checked" />
                                <label for="notice-yes" >通知</label>
                                <input type="radio" id="notice-no" name="notice" value="no" />
                                <label for="notice-no">不通知</label>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" class="submit-btn" name="mail-submit">保存邮件设置</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <?php require_once("footer.php"); ?>
</body>
</html>