<?php
// User name and password for authentication
$username = 'Gj8';
$password = 'gj8';

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    ($_SERVER['PHP_AUTH_USER'] != $username) || ($_SERVER['PHP_AUTH_PW'] != $password)) {
    // The user name/password are incorrect so send the authentication headers
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="Activity"');
    exit('<h2>管理员界面</h2>对不起，你必须使用正确的用户名密码');
}
?>
